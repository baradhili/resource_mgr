<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Resource;
use App\Models\Allocation;
use App\Models\Demand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ContractRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;
use App\Services\ResourceService;
use Illuminate\Pagination\LengthAwarePaginator;

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
        
        $contractResult = Contract::whereIn('resources_id', $resources->pluck('id'))
            ->orderBy('end_date', 'asc')
            ->get();

            // Get the current page from the request
            $page = $request->input('page', 1);
            $perPage = 10; // Define the number of items per page
    
            // Paginate the collection
            $contracts = new LengthAwarePaginator(
                $contractResult->forPage($page, $perPage),
                $contractResult->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
    
            return view('contract.index', compact('contracts','regions'))
                ->with('i', ($request->input('page', 1) - 1) * $contracts->perPage());

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contract = new Contract();

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

        return view('contract.show', compact('contract'));
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

    public function cleanProjects(Request $request) :RedirectResponse
    {
        $resourceID = $request->resource_id;
        $end_date = $request->end_date;

        $allocations = Allocation::where('resources_id', $resourceID)
            ->whereDate('allocation_date', '>=', $end_date)
            ->get();

        foreach ($allocations as $allocation) {
            $demand = new Demand();
            $demand->demand_date = $allocation->allocation_date;
            $demand->fte = $allocation->fte;
            $demand->projects_id = $allocation->projects_id;
            $demand->resource_type = "Solution Architect";
            $demand->save();

            $allocation->delete();
        }

        return Redirect::route('contracts.index')
            ->with('success', 'Allocations returned successfully');
    }
}
