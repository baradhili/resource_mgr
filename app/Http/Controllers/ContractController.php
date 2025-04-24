<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractRequest;
use App\Models\Allocation;
use App\Models\Contract;
use App\Models\Demand;
use App\Models\Resource;
use App\Models\Project;
use App\Services\CacheService;
use App\Services\ResourceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ContractController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * TODO: change these once allocation perms are seeded
     * The middleware configured here will be assigned to this controller's
     * routes.
     */
    protected $cacheService;

    protected $resourceService;

    public function __construct(CacheService $cacheService, ResourceService $resourceService)
    {
        $this->cacheService = $cacheService;
        $this->resourceService = $resourceService;
        // $this->middleware('teamowner', ['only' => ['create','store','update','edit','destroy']]);
        // $this->middleware('contract:view', ['only' => ['index']]);
        // $this->middleware('contract:create', ['only' => ['create','store']]);
        // $this->middleware('contract:update', ['only' => ['update','edit']]);
        // $this->middleware('contract:delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        // check if they are asking for a region
        $regionID = $request->input('region_id');
        // Collect our resources who have a current contract
        $resources = $this->resourceService->getResourceList($regionID);
        // collect teh regions from teh resources->region
        $regions = $resources->pluck('region')->filter()->unique()->values()->all();

        $old = $request->query('old');
        $search = $request->query('search');

        // assemble the query based on old and search values

        $query = Contract::query()
            ->whereIn('resources_id', $resources->pluck('id'))
            ->orderBy('end_date', 'asc');

        if (!$old) {
            $query->where('end_date', '>=', now());
        }

        if ($search) {
            $query->whereHas('resource', function ($resourceQuery) use ($search) {
                $resourceQuery->where('full_name', 'like', "%$search%");
            });
        }

        $contractResult = $query->get();

        // $contractResult = Contract::whereIn('resources_id', $resources->pluck('id'))
        //     ->orderBy('end_date', 'asc')
        //     ->get();

        // Get the current page from the request
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10); // Use request perPage if defined, otherwise default to 10

        // Paginate the collection
        $contracts = new LengthAwarePaginator(
            $contractResult->forPage($page, $perPage),
            $contractResult->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('contract.index', compact('contracts', 'regions'))
            ->with('i', ($request->input('page', 1) - 1) * $contracts->perPage());

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contract = new Contract;

        $resources = Resource::all(); // Retrieve all resources

        return view('contract.create', compact('contract', 'resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        if ($request->input('permanent')) {
            $validatedData['end_date'] = now()->addYears(100)->format('Y-m-d');
        }
        $validatedData['permanent'] = $request->has('permanent');

        Contract::create($validatedData);

        $this->cacheService->cacheResourceAvailability();

        return Redirect::route('contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $contract = Contract::find($id);
        //get teh resource for this contract
        $resource = $contract->resource;
        // calculate tenure in years between contract start and end date, round to 1 decimal place
        if ($resource->contracts && $resource->contracts->isNotEmpty()) {
            $firstContract = $resource->contracts->first();
            if ($firstContract->start_date && $firstContract->end_date) {
                $startDate = \Carbon\Carbon::parse($firstContract->start_date);
                $endDate = \Carbon\Carbon::parse($firstContract->end_date);
                $resource->tenure = round($endDate->diffInDays($startDate) / 365.25, 1);
            }
        }
        // find the project ids from teh resource's current allocations
        $endDate = $resource->contracts && $resource->contracts->isNotEmpty()
            ? $resource->contracts->first()->end_date
            : \Carbon\Carbon::now()->addMonths(3);
        $allocations = $resource->allocations()->whereBetween('allocation_date', [\Carbon\Carbon::now()->startOfMonth(), $endDate])->get();
        $uniqueProjectIds = $allocations->pluck('projects_id')->unique()->values()->all();
        $projects = Project::whereIn('id', $uniqueProjectIds)->get();
        $currentProjects = $projects;
        //filter all projects where the project end date is after or within one month of the resource's contract end date
        $currentProjects = $currentProjects->filter(function ($project) use ($resource) {

            if (!$resource->contracts || $resource->contracts->isEmpty() || !$project->end_date) {
                return false;
            }

            $contractEndDate = \Carbon\Carbon::parse($resource->contracts->first()->end_date);
            $projectEndDate = \Carbon\Carbon::parse($project->end_date);

            // Return true if project ends after contract OR within one month of contract end
            return $contractEndDate->lt($projectEndDate) ||
                $contractEndDate->copy()->subMonth()->lt($projectEndDate);
        });

        $resource->currentProjects = $currentProjects;

        return view('contract.show', compact('contract', 'resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $contract = Contract::find($id);

        $resources = Resource::all(); // Retrieve all resources

        return view('contract.edit', compact('contract', 'resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, Contract $contract): RedirectResponse
    {
        $existingContract = Contract::find($contract->id);

        if ($request->input('permanent')) {
            $request->merge(['end_date' => now()->addYears(100)->format('Y-m-d')]);
        }
        $validatedData = $request->validated();
        $validatedData['permanent'] = $request->has('permanent');

        $contract->update($validatedData);
        $this->cacheService->cacheResourceAvailability();

        return Redirect::route('contracts.index')
            ->with('success', 'Contract updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Contract::find($id)->delete();

        return Redirect::route('contracts.index')
            ->with('success', 'Contract deleted successfully');
    }

    /**
     * Move future allocations of a resource to demands and delete them.
     *
     * This function retrieves all allocations for a specified resource that
     * occur after a given end date. For each allocation, it creates a new
     * demand entry and then deletes the original allocation. The demand is
     * created with a fixed resource type of 'Solution Architect'.
     *
     * @param Request $request The HTTP request containing 'resource_id' and 'end_date'.
     * @return RedirectResponse Redirect to the contracts index page with a success message.
     */

    public function cleanProjects(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'end_date' => 'required|date',
        ]);
        $resourceID = $request->resource_id;
        $end_date = $request->end_date;

        $allocations = Allocation::where('resources_id', $resourceID)
            ->whereDate('allocation_date', '>=', $end_date)
            ->get();

        $resource_type = Resource::find($resourceID)->resourceType->id;

        \DB::beginTransaction();
        try {
            foreach ($allocations as $allocation) {
                $demand = new Demand;
                $demand->demand_date = $allocation->allocation_date;
                $demand->fte = $allocation->fte;
                $demand->projects_id = $allocation->projects_id;
                $demand->resource_type = $resource_type;
                $demand->save();

                $allocation->delete();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return Redirect::route('contracts.index')
                ->with('error', 'Failed to process allocations: ' . $e->getMessage());
        }
        return Redirect::route('contracts.index')
            ->with('success', 'Allocations returned successfully');
    }

}
