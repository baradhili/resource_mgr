<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\ChangeRequest;
use App\Models\Demand;
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

class ChangeRequestController extends Controller
{
    protected $cacheService;

    /**
     * Create a new controller instance.
     */
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
        // get user
        $user = Auth::user();
        $resourceTypes = $user->ownedTeams->pluck('resource_type')->toArray();
        $resourceTypes = ResourceType::whereIn('name', $resourceTypes)->pluck('id')->toArray();

        // get the resources we manage
        $resources = $this->resourceService->getResourceList()->pluck('id')->toArray();
        // Log::info("resources: " . json_encode($resources));

        $showHistory = (int) $request->query('history', 0);

        $changeRequests = ChangeRequest::with(['record'])
            ->whereHasMorph(
                'record',
                [Allocation::class, Demand::class],
                function ($query, $type) use ($resources, $resourceTypes) {
                    if ($type === Allocation::class) {
                        $query->when(! empty($resources), function ($query) use ($resources) {
                            $query->whereIn('resources_id', $resources);
                        });
                    } elseif ($type === Demand::class) {
                        $query->when(! empty($resourceTypes), function ($query) use ($resourceTypes) {
                            $query->whereIn('resource_type', $resourceTypes);
                        });
                    }
                }
            )
            ->when($showHistory === 1, function ($query) {
                return $query->where('status', '!=', 'pending');
            }, function ($query) {
                return $query->where('status', 'pending');
            })
            ->paginate();

        // if the record type is allocation, get the allocation resource->full_name and insert into a new parameter "subject"
        $changeRequestsToRemove = [];
        foreach ($changeRequests as $changeRequest) {
            if ($changeRequest->record_type === Allocation::class) {
                $allocation = $changeRequest->record;

                $changeRequest->subject = "{$allocation->resource->full_name} on project {$allocation->project->name} for date {$allocation->allocation_date}";

            } elseif ($changeRequest->record_type === Demand::class) {
                $demand = $changeRequest->record;
                // Log::info("checking if its one of our resource types - {$demand->resource_type}");
                // if this isn't one of our $resource_types then remove from $changeRequests

                // if $demand->resource_type is a number then find ResourceType->name
                if (is_numeric($demand->resource_type)) {
                    $demand->resource_type = ResourceType::find($demand->resource_type)->name;
                }
                $changeRequest->subject = "{$demand->resource_type} on project {$demand->project->name} for date {$demand->demand_date}";

            }
        }

        return view('change-request.index', compact('changeRequests'))
            ->with('i', ($request->input('page', 1) - 1) * $changeRequests->perPage());
    }

    public function approve(ChangeRequest $changeRequest)
    {
        // Verify the change request
        if ($changeRequest->status !== 'pending') {
            return;
        }

        // Update the allocation or demand
        if ($changeRequest->record_type === Allocation::class) {
            $allocation = $changeRequest->record;
            $allocation->{$changeRequest->field} = $changeRequest->new_value;
            $allocation->save();
        } elseif ($changeRequest->record_type === Demand::class) {
            $demand = $changeRequest->record;
            $demand->{$changeRequest->field} = $changeRequest->new_value;
            $demand->save();
        }

        // Update the change request status
        $changeRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approval_date' => Carbon::now(),
        ]);
        // refresh cache for allocation
        $this->cacheService->cacheResourceAllocation();

        return redirect()->route('change-requests.index')->with('success', 'Change request approved');

    }

    /**
     * Display the specified resource.
     */
    public function show(ChangeRequest $changeRequest): View
    {
        return view('change-request.show', compact('changeRequest'));
    }

    public function destroy(ChangeRequest $changeRequest): RedirectResponse
    {
        $changeRequest->delete();

        return Redirect::route('change-requests.index')
            ->with('success', 'ChangeRequest deleted successfully');
    }
}
