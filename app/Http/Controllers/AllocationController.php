<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Resource;
use App\Models\Project;
use App\Models\Demand;
use App\Models\ResourceType;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AllocationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use \avadim\FastExcelReader\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\CacheService;

class AllocationController extends Controller
{

    protected $cacheService;

    /**
     * Create a new controller instance.
     *
     * TODO: change these once allocation perms are seeded
     * The middleware configured here will be assigned to this controller's
     * routes.
     */
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        if (!$user->can('allocations.index')) {
            return Redirect::route('home')->with('warning', 'You do not have the necessary permissions to view the allocations page.');
        }
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

        // Collect our resources who have a current contract
        $resources = $this->ResourceList();

        // Modify resource names to add [c] if the resource is not permanent
        foreach ($resources as $resource) {
            if (isset($resource->contracts[0]) && !$resource->contracts[0]->permanent) {
                $resource->full_name .= ' [c]';
            }
        }

        if (!Cache::has('resourceAllocation')) {
            $this->cacheService->cacheResourceAllocation();
            $resourceAllocation = Cache::get('resourceAllocation');
        } else {
            $resourceAllocation = Cache::get('resourceAllocation');
        }

        return view('allocation.index', compact('resources', 'resourceAllocation', 'nextTwelveMonths'))
            ->with('i', ($request->input('page', 1) - 1) * $resources->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (!$user->can('allocations.create')) {
            return Redirect::route('allocations.index')->with('warning', 'You do not have the necessary permissions to view the allocations page.');
        }
        $allocation = new Allocation();
        $resources = Resource::all(); // Retrieve all resources
        $projects = Project::all(); // Retrieve all projects

        return view('allocation.create', compact('allocation', 'resources', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AllocationRequest $request): RedirectResponse
    {
        Allocation::create($request->validated());

        return Redirect::route('allocations.index')
            ->with('success', 'Allocation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        if (!$user->can('allocations.show')) {
            return Redirect::route('allocations.index')->with('warning', 'You do not have the necessary permissions to view the allocations page.');
        }
        $allocation = Allocation::find($id);

        return view('allocation.show', compact('allocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($project_id, Request $request): RedirectResponse
    {
        if ($request->input('start_date')) {
            if ($request->input('end_date')) {
                $allocationArray = Allocation::where('projects_id', $project_id)
                    ->whereBetween('allocation_date', [$startDate, $endDate])
                    ->where('resources_id', '=', $request->resource_id)
                    ->get();
            } else {
                $allocationArray = Allocation::where('projects_id', $project_id)
                    ->where('allocation_date', '>=', $startDate)
                    ->where('resources_id', '=', $request->resource_id)
                    ->get();
            }
        } else {
            $allocationArray = Allocation::where('projects_id', $project_id)
                ->where('resources_id', '=', $request->resource_id)
                ->get();
        }

        foreach ($allocationArray as $allocation) {
            $demand = new Demand();
            $demand->demand_date = $allocation->allocation_date;
            $demand->fte = $allocation->fte;
            $demand->projects_id = $allocation->projects_id;
            $demand->resource_type = "Solution Architect";
            $demand->save();

            $allocation->delete();
        }

        return Redirect::route('allocations.index');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function editOne(Request $request): View
    {
        $allocation_date = Carbon::parse($request->monthKey . '-01')->format('Y-m-d');
        $allocation = Allocation::where('projects_id', $request->projectId)
            ->where('resources_id', $request->resourceId)
            ->where('allocation_date', $allocation_date)
            ->first();
        $resources = Resource::all();
        $projects = Project::all();
        $form_type = "one";

        return view('allocation.edit', compact('allocation', 'resources', 'projects', 'form_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AllocationRequest $request, Allocation $allocation): RedirectResponse
    {
        $allocation->update($request->validated());
        $this->cacheService->cacheResourceAllocation();
        return Redirect::route('allocations.index')
            ->with('success', 'Allocation updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        if (!$user->can('allocations.delete')) {
            return Redirect::route('allocations.index')->with('warning', 'You do not have the necessary permissions to view the allocations page.');
        }
        Allocation::find($id)->delete();

        return Redirect::route('allocations.index')
            ->with('success', 'Allocation deleted successfully');
    }

    private function ResourceList()
    {
        //get user 
        $user = Auth::user();
        // Check if the user is an owner of a team
        if ($user->ownedTeams()->count() > 0) {
            // Get the team's resources
            Log::info("User is an owner of a team with resource type: " . $user->ownedTeams()->first()->resource_type);
            $resource_type = ResourceType::where('name', $user->ownedTeams()->first()->resource_type)->first()->id;
            Log::info("resource type: " . json_encode($resource_type));
            $resources = Resource::whereHas('contracts', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
                ->where('resource_type', $resource_type)
                ->with('contracts')->paginate();
            Log::info("resources: " . json_encode($resources));
        } elseif ($user->reportees->count() > 0) {
            // check if the user is a manager
            Log::info("User is a manager");
            $reportees = $user->reportees;
            //for each linked resource contract, check if the start date is before now and the end date is after now
            $resourceIDs = $user->reportees->pluck('resource.id')->toArray();
            $resources = Resource::whereIn('id', $resourceIDs)->whereHas('contracts', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })->with('contracts')->paginate();
        } else {
            Log::info("User is not an owner of a team or a manager");
            // otherwise just return the user's resource
            $resources = Resource::where('id', $user->resource_id)->with('contracts')->paginate();
        }
        return $resources;
    }
}
