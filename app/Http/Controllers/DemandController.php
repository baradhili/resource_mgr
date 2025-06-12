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
        $resource_types = $user->ownedTeams->pluck('resource_type')->toArray();
        // If the user doesn't own any resource types, return the home view
        if (empty($resource_types)) {
            return view('home');
        }

        // Get the resources with contracts in the next 12 months
        $resources = $this->resourceService->getResourceList();

        // Get the projects_id from demands in our window
        $demandProjectIds = Demand::whereBetween('demand_date', [$startDate, $endDate])
            ->whereIn('resource_type', array_map('intval', $resource_types))
            ->pluck('projects_id')
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
            $resourceType = Demand::where('projects_id', '=', $project->id)->value('resource_type');
            // If the project doesn't have a resource type, continue to the next project
            if (!in_array($resourceType, $resource_types)) {
                continue;
            }
            // Get the acronym for the resource type
            if (is_numeric($resourceType)) {
                $resourceType = ResourceType::findOrFail($resourceType)->name;
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
                'demands' => [],
            ];

            // Loop through the months and get the total allocation for the project
            foreach ($nextTwelveMonths as $month) {
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                $totalAllocation = Demand::where('demand_date', '=', $monthStartDate)
                    ->where('projects_id', '=', $project->id)
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

        // Pagination
        $page = $request->input('page', 1);
        $perPage = 10; // Set the number of items per page
        $offset = ($page * $perPage) - $perPage;

        // Get the results for the current page
        $result = array_slice($data, $offset, $perPage, true);
        // Create a paginator instance
        $paginator = new LengthAwarePaginator($result, count($data), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // Return the view
        return view('demand.index', compact('paginator', 'nextTwelveMonths', 'resources'))
            ->with('i', ($request->input('page', 1) - 1) * $perPage);
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
        $demand->projects_id = null;
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
                'projects_id' => $projectID,
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

        $demand_raw = Demand::where('projects_id', $id)->get();
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
        $demandArray = Demand::where('projects_id', $project_id)
            ->whereBetween('demand_date', [now()->startOfYear(), now()->endOfYear()->addYear()])
            ->get();

        foreach ($demandArray as $demand) {
            $allocation = new Allocation;
            $allocation->allocation_date = $demand->demand_date;
            $allocation->resources_id = $request->resource_id;
            $allocation->fte = $demand->fte;
            $allocation->projects_id = $demand->projects_id;
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
     */
    public function editFullDemand($project_id): View
    {
        $demandArray = Demand::where('projects_id', $project_id)
            ->whereBetween('demand_date', [now()->startOfYear(), now()->endOfYear()->addYear()])
            ->get();

        $demand = new \stdClass;
        $demand->name = $demandArray->first()->project->name;
        $demand->start_date = Carbon::parse($demandArray->min('demand_date'))->format('Y-m-d');
        $demand->end_date = Carbon::parse($demandArray->max('demand_date'))->format('Y-m-d');
        $demand->status = $demandArray->first()->status;
        $demand->resource_type = $demandArray->first()->resource_type;
        $demand->fte = $demandArray->first()->fte;
        $demand->projects_id = $project_id;
        $demand->demand_id = $demandArray->first()->id;

        $projects = Project::all();
        $resourceTypes = ResourceType::all();

        return view('demand.edit', compact('demand', 'projects', 'resourceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $projects_id): RedirectResponse
    {
        // if (!is_numeric($request->input('projects_id'))) {
        //     $project = Project::where('name', $request->input('projects_id'))->first();
        //     $projectID = $project->id ?? null;
        //     if (is_null($projectID)) {
        //         $project = Project::updateOrCreate(
        //             ['name' => $request->input('projects_id')]
        //         );
        //         $projects_id = $project->id;
        //     }
        // } else {
        // $projects_id = $request->input('projects_id');
        // }
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
            'resource_type' => 'required|string',
            'fte' => 'required|numeric',
            'demand_id' => 'required|numeric',
        ]);

        $demand = Demand::findOrFail($request->input('demand_id'));

        $oldProjects_id = $demand->projects_id;

        $storedDemand = Demand::where('projects_id', $demand->oldProjects_id)
            ->get()
            ->keyBy('demand_date')
            ->toArray();

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        $minDemandDate = array_key_first($storedDemand);
        $maxDemandDate = array_key_last($storedDemand);

        if ($startDate->lt($minDemandDate) || $endDate->gt($maxDemandDate)) {
            Demand::where('projects_id', $oldProjects_id)->delete();

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
                    'projects_id' => $projects_id,
                ]);
                $monthStartDate->addMonth();
            }

        }

        Demand::where('projects_id', $oldProjects_id)
            ->update([
                'status' => $request->input('status'),
                'resource_type' => $request->input('resource_type'),
                'fte' => $request->input('fte'),
                'projects_id' => $oldProjects_id,
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
        Demand::where('projects_id', $id)
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

        // Collect the projects_id from demands in our window
        $demandIDs = Demand::whereBetween('demand_date', [$startDate, $endDate])
            ->pluck('projects_id')
            ->unique()
            ->toArray();

        $projects = Project::whereIn('id', $demandIDs)
            ->select('id', 'name')
            ->addSelect([
                'status' => Demand::select('status')
                    ->whereColumn('projects_id', 'projects.id')
                    ->orderBy('demand_date')
                    ->limit(1),
            ])
            ->get();
        $i = 2;
        foreach ($projects as $project) {

            $sheet->setCellValue([1, $i], $project->name);

            $resource_type_code = Demand::where('projects_id', '=', $project->id)->value('resource_type');
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
                    ->where('projects_id', '=', $project->id)
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
