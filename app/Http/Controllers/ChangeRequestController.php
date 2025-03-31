<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use App\Models\Allocation;
use App\Models\Demand;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ChangeRequestRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Services\CacheService;

class ChangeRequestController extends Controller
{
    protected $cacheService;

    /**
     * Create a new controller instance.
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
        $showHistory = (int) $request->query('history', 0);
        if ($showHistory === 1) {
            $changeRequests = ChangeRequest::with(['record'])
                ->whereHasMorph(
                    'record',
                    [Allocation::class, Demand::class]
                )
                ->where('status', '!=', 'pending')
                ->paginate();
        } else {
            $changeRequests = ChangeRequest::with(['record'])
                ->whereHasMorph(
                    'record',
                    [Allocation::class, Demand::class]
                )
                ->where('status', 'pending')
                ->paginate();
        }
        //if the record type is allocation, get the allocation resource->full_name and insert into a new parameter "subject"
        foreach ($changeRequests as $changeRequest) {
            if ($changeRequest->record_type === Allocation::class) {
                $allocation = $changeRequest->record;
                $changeRequest->subject = "{$allocation->resource->full_name} on project {$allocation->project->name} for date {$allocation->allocation_date}";
            } elseif ($changeRequest->record_type === Demand::class) {
                $demand = $changeRequest->record;
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
        //refresh cache for allocation
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
