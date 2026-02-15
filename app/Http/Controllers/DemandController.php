<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Demand;
use App\Models\Project;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Services\CacheService;
use App\Services\ResourceService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Pagination\LengthAwarePaginator;

class DemandController extends Controller
{
    protected $cacheService;

    protected $resourceService;

    public function __construct(CacheService $cacheService, ResourceService $resourceService)
    {
        $this->cacheService = $cacheService;
        $this->resourceService = $resourceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // We need to display a list of projects with their demand for resources
        // in the next 12 months. We'll build an array of the months and their
        // corresponding year and month name for the view.
        $nextTwelveMonths = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F'),
            ];
        }

        // The period we're interested in is from the start of the current month
        // to the start of the following year. We'll get the start date of the
        // current month and the start date of the following year.
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addYear()->startOfMonth();

        // Get the user
        $user = Auth::user();
        // Get the resource types that the user owns
        // Keep only numeric, non-null IDs and cast to int once
        $resource_types = array_values(
            array_map(
                'intval',
                array_filter(
                    $user->ownedTeams->pluck('resource_type')->toArray(),
                    'is_numeric'
                )
            )
        );
        // If the user doesn't own any resource types, return the home view
        if (empty($resource_types)) {
            return view('home');
        }

        // Get the resources with contracts in the next 12 months
        $resources = $this->resourceService->getResourceList();

        // Get the project_id from demands in our window
        $demandProjectIds = Demand::whereBetween('demand_date', [$startDate, $endDate])
            ->whereIn('resource_type', $resource_types)
            ->pluck('project_id')
            ->unique()
            ->values()
            ->all();

        // Eager load the projects with their names and demands
        $projects = Project::whereIn('id', $demandProjectIds)
            ->with('demands')
            ->get();

        // Create an array of project data
        $data = [];
        foreach ($projects as $project) {
            // Get the resource type for the project
            $resourceType = optional($project->demands->first())->resource_type;
            // If the project doesn't have a resource type, continue to the next project
            if (!in_array($resourceType, $resource_types)) {
                continue;
            }
            // Get the acronym for the resource type
            if (is_numeric($resourceType)) {
                $resourceType = ResourceType::findOrFail($resourceType)->name ?? 'N/A';
            }

            $acronym = '';
            if ($resourceType) {
                $words = explode(' ', trim($resourceType));
                $acronym = strtoupper(substr($words[0], 0, 1));
                if (count($words) > 1) {
                    $acronym .= strtoupper(substr($words[1], 0, 1));
                }
            }

            // Create the project data array
            $projectData = [
                'id' => $project->id,
                'name' => $project->name,
                'empowerID' => $project->empowerID, // Collect empowerID
                'type' => $acronym,
                'type_name' => $resourceType,
                'demands' => [],
            ];

            // Loop through the months and get the total allocation for the project
            foreach ($nextTwelveMonths as $month) {
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                $totalAllocation = Demand::where('demand_date', '=', $monthStartDate)
                    ->where('project_id', '=', $project->id)
                    ->pluck('fte')
                    ->first();
                $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // If the total allocation is greater than 0, add the allocation to the project data
                if ($totalAllocation > 0) {
                    $projectData['demands'][$key] = $totalAllocation;
                }
            }

            // Add the project data to the data array
            $data[] = $projectData;
        }

        // Filter out entries with an empty demands array
        $data = array_filter($data, function ($item) {
            return !empty($item['demands']);
        });

        // Pagination - sanitize inputs
        $page = max(1, (int) $request->input('page', 1));
        $perPage = max(1, min((int) $request->input('perPage', 10), 100));
        $offset = ($page - 1) * $perPage;

        // Get the results for the current page
        $result = array_slice($data, $offset, $perPage, true);
        // Create a paginator instance
        $paginator = new LengthAwarePaginator($result, count($data), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // Return the view
        return view('demand.index', compact('paginator', 'nextTwelveMonths', 'resources'))
            ->with('i', ($page - 1) * $perPage);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $demand = new \stdClass;
        $demand->name = '';
        $demand->start_date = '';
        $demand->end_date = '';
        $demand->status = '';
        $demand->resource_type = '';
        $demand->fte = 0.00;
        $demand->project_id = null;
        $demand->source = 'Manual';

        $projects = Project::all();
        $resourceTypes = ResourceType::all();

        return view('demand.create', compact('demand', 'projects', 'resourceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));
        $projectID = $request->input('project_id');

        $monthStartDate = Carbon::create($startDate->year, $startDate->month, 1);
        $monthEndDate = Carbon::create($endDate->year, $endDate->month, 1)->endOfMonth();

        while ($monthStartDate->lte($monthEndDate)) {
            $demandLength = min($monthEndDate, $monthStartDate->copy()->endOfMonth())->diffInDays($monthStartDate);
            $fte = $request->input('fte') * $demandLength / $monthStartDate->diffInDays($monthStartDate->copy()->endOfMonth());
            Demand::create([
                'demand_date' => $monthStartDate,
                'fte' => $fte,
                'status' => $request->input('status'),
                'resource_type' => $request->input('resource_type'),
                'project_id' => $projectID,
            ]);
            $monthStartDate->addMonth();
        }

        return Redirect::route('demands.index')
            ->with('success', 'Demand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {

        $demand_raw = Demand::where('project_id', $id)->get();
        $project = Project::findOrFail($id);
        $demand = new \stdClass;
        $demand->name = $project->name;
        $demand->start_date = $demand_raw->min('demand_date');
        $demand->end_date = $demand_raw->max('demand_date');
        $demand->status = $demand_raw->first()->status;
        $demand->resource_type = $demand_raw->first()->resource_type;
        $demand->total_fte = $demand_raw->sum('fte');
        $demand->fte = $demand_raw->first()->fte;

        return view('demand.show', compact('demand'));
    }

    /**
     * Show the form for editing the specified resource.
     * - TODO we need to make sure we don't wipe out other demands
     * - TODO we should run to the end of the demand, or deal with each month by itself
     */
    public function edit($project_id, Request $request): RedirectResponse
    {
        $demandArray = Demand::where('project_id', $project_id)
            ->whereBetween('demand_date', [now()->startOfYear(), now()->endOfYear()->addYear()])
            ->get();

        foreach ($demandArray as $demand) {
            $allocation = new Allocation;
            $allocation->allocation_date = $demand->demand_date;
            $allocation->resources_id = $request->resource_id;
            $allocation->fte = $demand->fte;
            $allocation->project_id = $demand->project_id;
            $allocation->status = $demand->status;
            $allocation->source = $demand->source;
            $allocation->save();

            $demand->delete();
        }

        // Update the cache
        $this->cacheService->cacheResourceAllocation();

        return Redirect::route('demands.index')
            ->with('success', 'Resource assigned to project successfully.');
    }

    /**
     * Edit the overall demand of a project
     *
     * @param  int  $project_id  The id of the project
     * @param string $resource_type  The name of the resource type we're looking for
     */
    public function editFullDemand($project_id, $resource_type): View
    {
        // Find the project we're interested in
        $project = Project::find($project_id);

        // Find the resource type we're interested in
        $resource_type = ResourceType::where('name', $resource_type)->first();

        // Get all demands for the project, grouped by resource type, and only
        // where the FTE is greater than 0
        $demands = Demand::selectRaw('resource_type, MIN(demand_date) as start, MAX(demand_date) as end, AVG(fte) as fte')
            ->where('project_id', $project->id)
            ->where('fte', '>', 0)
            ->groupBy('resource_type')
            ->get();

        // Map the results of the query to a new collection, converting the
        // start and end dates to 'M-Y' format
        $demands = $demands->map(function ($demand) {
            $demand->start = date('M-Y', strtotime($demand->start));
            $demand->end = date('M-Y', strtotime($demand->end));

            // Find the resource type name for the resource type id
            $demand->resource_type = ResourceType::find($demand->resource_type)->name;

            return $demand;
        });

        // Find the first demand of the project, which will be used to fill in
        // the form
        $firstDemand = Demand::where('project_id', $project->id)
            ->where('resource_type', $resource_type->id)
            ->first();

        // Create a new stdClass object to hold the data for the form
        $demand = new \stdClass;

        // Fill in the form data
        $demand->demand_id = 1;
        $demand->name = $project->name;
        $demand->start_date = $demands->min('start') ? date('Y-m-01', strtotime($demands->min('start'))) : null;
        $demand->end_date = $demands->max('end') ? date('Y-m-01', strtotime($demands->max('end'))) : null;
        $demand->status = $firstDemand->status;
        $demand->resource_type = $resource_type->id;
        $demand->fte = $firstDemand->fte;
        $demand->project_id = $project->id;

        //make sure we keep these for comparison
        session()->put('old_demand', [
            'project_id' => $demand->project_id,
            'start_date' => $demand->start_date,
            'end_date' => $demand->end_date,
            'status' => $demand->status,
            'resource_type' => $demand->resource_type,
            'fte' => $demand->fte,
        ]);

        // Pass the data to the view
        $projects = Project::all();
        $resourceTypes = ResourceType::all();

        return view('demand.edit', compact('demand', 'projects', 'resourceTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $project_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $project_id): RedirectResponse
    {
        //pull all "old" data for comparison
        $oldDemand = session()->get('old_demand', []);

        $old_project_id = $oldDemand['project_id'] ?? null;
        $old_start_date = $oldDemand['start_date'] ?? null;
        $old_end_date = $oldDemand['end_date'] ?? null;
        $old_status = $oldDemand['status'] ?? null;
        $old_resource_type = $oldDemand['resource_type'] ?? null;
        $old_fte = $oldDemand['fte'] ?? null;

        $project = Project::where('name', $request->input('name'))->firstOrFail();
        $demand = Demand::where('project_id', $project->id)
            ->where('demand_date', $request->input('old_start_date'))
            ->first();

        $oldProject_id = $old_project_id;

        $storedDemand = Demand::where('project_id', $old_start_date)
            ->get()
            ->keyBy('demand_date')
            ->toArray();

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $minDemandDate = array_key_first($storedDemand);
        $maxDemandDate = array_key_last($storedDemand);

        if ($startDate->lt($minDemandDate) || $endDate->gt($maxDemandDate)) {
            Demand::where('project_id', $oldProject_id)->delete();

            $monthStartDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfMonth();
            $monthEndDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));

            while ($monthStartDate->lte($monthEndDate)) {
                $demandLength = min($monthEndDate, $monthStartDate->copy()->endOfMonth())->diffInDays($monthStartDate);

                $fte = $request->input('fte') * $demandLength / $monthStartDate->diffInDays($monthStartDate->copy()->endOfMonth());

                Demand::create([
                    'demand_date' => $monthStartDate,
                    'fte' => $fte,
                    'status' => $request->input('status'),
                    'resource_type' => $request->input('resource_type'),
                    'project_id' => $project_id,
                ]);

                $monthStartDate->addMonth();
            }
        }

        Demand::where('project_id', $oldProject_id)
            ->update([
                'status' => $request->input('status'),
                'resource_type' => $request->input('resource_type'),
                'fte' => $request->input('fte'),
                'project_id' => $oldProject_id,
            ]);

        return Redirect::route('demands.index')
            ->with('success', 'Demand updated successfully');
    }

    /**
     * Destroy all demands for a given project that are in the next year
     *
     * @param  int  $id  The ID of the project
     * @return RedirectResponse To the demands index page
     */
    public function destroy($id): RedirectResponse
    {
        Demand::where('project_id', $id)
            ->whereBetween('demand_date', [now()->startOfMonth(), now()->endOfMonth()->addYear()])
            ->delete();

        return Redirect::route('demands.index')
            ->with('success', 'Demands deleted successfully');
    }

    public function exportDemands()
    {
        // Build our next twelve month array
        $nextTwelveMonths = [];

        // start labelling
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Project Name');
        $sheet->setCellValue('B1', 'Resource Type');
        $sheet->setCellValue('C1', 'Status');
        $sheet->setCellValue('D1', 'Month');

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F'),
            ];
            $sheet->setCellValue([$i + 4, 1], $date->format('M') . ' ' . $date->year);
        }
        //  Start and end dates for the period
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addYear()->startOfMonth();

        // Collect the project_id from demands in our window
        $demandIDs = Demand::whereBetween('demand_date', [$startDate, $endDate])
            ->pluck('project_id')
            ->unique()
            ->toArray();

        $projects = Project::whereIn('id', $demandIDs)
            ->select('id', 'name')
            ->addSelect([
                'status' => Demand::select('status')
                    ->whereColumn('project_id', 'projects.id')
                    ->orderBy('demand_date')
                    ->limit(1),
            ])
            ->get();
        $i = 2;
        foreach ($projects as $project) {

            $sheet->setCellValue([1, $i], $project->name);

            $resource_type_code = Demand::where('project_id', '=', $project->id)->value('resource_type');
            //look up ResourceType object
            $resource_type = ResourceType::find($resource_type_code);

            if ($resource_type && $resource_type->name) {
                $resource_type_name = $resource_type->name;
            } else {
                $resource_type_name = 'undefined';
            }
            $sheet->setCellValue([2, $i], $resource_type_name);
            $sheet->setCellValue([3, $i], $project->status);

            $j = 4;
            foreach ($nextTwelveMonths as $month) {
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                $demand = Demand::where('demand_date', '=', $monthStartDate)
                    ->where('project_id', '=', $project->id)
                    ->pluck('fte')
                    ->first();
                $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // Add the calculated base availability to the resource availability array - only if not zero
                if ($demand > 0) {
                    $sheet->setCellValue([$j, $i], $demand);
                }
                $j++;
            }
            $i++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="demands.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        return Redirect::route('demands.index')
            ->with('success', 'Demand exported successfully');
    }
}
