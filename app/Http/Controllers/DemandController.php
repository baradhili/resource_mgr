<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemandRequest;
use App\Models\Allocation;
use App\Models\Contract;
use App\Models\Demand;
use App\Models\Project;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Build our next twelve month array
        $nextTwelveMonths = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F')
            ];
        }
        //  Start and end dates for the period
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addYear()->startOfMonth();

        // Collect resources with contracts in the next 12 months
        $resources = Resource::whereIn(
            'id',
            Contract::where(function ($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                    ->where(function ($q) use ($startDate) {
                        $q->where('end_date', '>=', $startDate)
                            ->orWhereNull('end_date');
                    });
            })
                ->pluck('resources_id')
                ->unique()
                ->values()
        )->get();

        // Collect the projects_id from demands in our window
        $demandIDs = Demand::whereBetween('demand_date', [$startDate, $endDate])
            ->pluck('projects_id')
            ->unique()
            ->values()
            ->all();

        // Eager load the projects with their names
        $projects = Project::whereIn('id', $demandIDs)
            ->with('demands') // Eager load the demands relationship
            ->paginate();

        $demandArray = [];
        // For each project - find the allocations for the period

        foreach ($projects as $project) {

            $resource_type = Demand::where('projects_id', '=', $project->id)->value('resource_type');
            if ($resource_type) {
                $words = explode(' ', trim($resource_type));
                $acronym = '';
                for ($i = 0; $i < min(2, count($words)); $i++) {
                    $acronym .= strtoupper(substr($words[$i], 0, 1));
                }
            } else {
                $acronym = '';
            }

            $demandArray[$project->id] = [
                'name' => $project->name,
                'type' => $acronym,
            ];

            foreach ($nextTwelveMonths as $month) {
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                $totalAllocation = Demand::where('demand_date', '=', $monthStartDate)
                    ->where('projects_id', '=', $project->id)
                    ->pluck('fte')
                    ->first();
                $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // Add the calculated base availability to the resource availability array - only if not zero
                if ($totalAllocation > 0) {
                    $demandArray[$project->id]['demand'][$key] = $totalAllocation;
                }
            }
        }
        // Log::info("return: " . print_r($demandArray, true));
        return view('demand.index', compact('projects', 'demandArray', 'nextTwelveMonths', 'resources'))
            ->with('i', ($request->input('page', 1) - 1) * $projects->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $demand = new Demand();

        return view('demand.create', compact('demand'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DemandRequest $request): RedirectResponse
    {
        Demand::create($request->validated());

        return Redirect::route('demands.index')
            ->with('success', 'Demand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        Log::info("in show");
        $demand = Demand::find($id);

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
            $allocation = new Allocation();
            $allocation->allocation_date = $demand->demand_date;
            $allocation->resources_id = $request->resource_id;
            $allocation->fte = $demand->fte;
            $allocation->projects_id = $demand->projects_id;
            $allocation->status = "Proposed";
            $allocation->save();

            $demand->delete();
        }

        return Redirect::route('demands.index')
            ->with('success', 'Resource assigned to project successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandRequest $request, Demand $demand): RedirectResponse
    {
        $demand->update($request->validated());

        return Redirect::route('demands.index')
            ->with('success', 'Demand updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Demand::find($id)->delete();

        return Redirect::route('demands.index')
            ->with('success', 'Demand deleted successfully');
    }


    public function exportDemands()
    {
        // Build our next twelve month array
        $nextTwelveMonths = [];

        // start labelling
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Project Name');
        $sheet->setCellValue('B1', 'Resource Type');
        $sheet->setCellValue('C1', 'Month');

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F')
            ];
            $sheet->setCellValue([$i + 3, 1], $date->format('M') . ' ' . $date->year);
        }
        //  Start and end dates for the period
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addYear()->startOfMonth();

        // Collect the projects_id from demands in our window
        $demandIDs = Demand::whereBetween('demand_date', [$startDate, $endDate])
            ->pluck('projects_id')
            ->unique()
            ->values()
            ->all();

        // Eager load the projects with their names
        $projects = Project::whereIn('id', $demandIDs)
            ->with('demands') // Eager load the demands relationship
            ->get();

        $i = 2;
        foreach ($projects as $project) {
            Log::info("project name = " . $project->name);
            $sheet->setCellValue([1, $i], $project->name);

            $resource_type = Demand::where('projects_id', '=', $project->id)->value('resource_type');
            if ($resource_type) {
                $words = explode(' ', trim($resource_type));
                $acronym = '';
                for ($k = 0; $k < min(2, count($words)); $k++) {
                    $acronym .= strtoupper(substr($words[$k], 0, 1));
                }
            } else {
                $acronym = '';
            }
            $sheet->setCellValue([2, $i], $acronym);

            $j = 3;
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
