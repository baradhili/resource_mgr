<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest;
use App\Models\Leave;
use App\Models\Resource;
use App\Services\CacheService;
use App\Services\ResourceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LeaveController extends Controller
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
     *
     * This is the main method that will grab all the leaves that are
     * associated with resources. It will filter the results based on
     * the following criteria:
     *  - If the user asks for a specific region, it will only grab
     *    resources from that region
     *  - If the user asks for only current leaves, it will filter out
     *    all leaves that have end dates in the past
     *  - If the user asks for leaves that match a specific search
     *    term, it will only grab leaves that have a resource whose
     *    full_name matches the search term
     *
     * The method will then paginate the results and return the view
     * with the paginated results and the search/filter options
     */
    public function index(Request $request): View
    {
        // Get the user who is making the request
        $user = auth()->user();

        // Get the region ID from the request, if it exists
        $regionID = $request->input('region_id');

        // Get the list of resources that have a current contract
        $resources = $this->resourceService->getResourceList($regionID);

        // Get the list of regions from the resources->region
        $regions = $resources->pluck('region')->filter()->unique()->values()->all();

        // Get the old parameter from the request, if it exists
        $old = $request->query('old');

        // Get the search parameter from the request, if it exists
        $search = $request->query('search');

        // Build the query based on the old and search values
        $query = Leave::query()
            ->whereIn('resources_id', $resources->pluck('id'));

        // If the user asked for only current leaves, filter out all leaves that have end dates in the past
        if (! $old) {
            $query->where('end_date', '>=', now());
        }

        // If the user asked for a specific search term, filter out all leaves that don't match the search term
        if ($search) {
            $query->whereHas('resource', function ($resourceQuery) use ($search) {
                $resourceQuery->where('full_name', 'like', "%$search%");
            });
        }

        // Execute the query and get the results
        $leaveResult = $query->get();

        // Get and sanitize pagination inputs
        $page = max(1, (int) $request->input('page', 1));
        $perPage = max(1, min((int) $request->input('perPage', 10), 100));

        // Paginate the collection
        $leaves = new LengthAwarePaginator(
            $leaveResult->forPage($page, $perPage),
            $leaveResult->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('leave.index', compact('leaves', 'regions'))
            ->with('i', ($page - 1) * $perPage);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $leave = new Leave;

        $resources = Resource::all(); // Retrieve all resources

        return view('leave.create', compact('leave', 'resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveRequest $request): RedirectResponse
    {
        Leave::create($request->validated());

        // refesh availability
        $this->cacheService->cacheResourceAvailability();

        return Redirect::route('leaves.index')
            ->with('success', 'Leave created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $leave = Leave::find($id);

        return view('leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $leave = Leave::find($id);

        $resources = Resource::all(); // Retrieve all resources

        return view('leave.edit', compact('leave', 'resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeaveRequest $request, Leave $leave): RedirectResponse
    {
        $leave->update($request->validated());

        // refesh availability
        $this->cacheService->cacheResourceAvailability();

        return Redirect::route('leaves.index')
            ->with('success', 'Leave updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Leave::find($id)->delete();

        return Redirect::route('leaves.index')
            ->with('success', 'Leave deleted successfully');
    }
}
