<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Resource;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DemandRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

            $demandArray[$project->id] = [
                'name' => $project->name,
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
Log::info("return: " . print_r($projects, true));
        return view('demand.index', compact('projects', 'demandArray','nextTwelveMonths'))
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
        $demand = Demand::find($id);

        return view('demand.show', compact('demand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $demand = Demand::find($id);

        return view('demand.edit', compact('demand'));
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

    
}
