<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ChangeRequestRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ChangeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $changeRequests = ChangeRequest::paginate();

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
        if ($changeRequest->record_type === 'allocation') {
            $allocation = $changeRequest->allocation;
            $allocation->{$changeRequest->field} = $changeRequest->new_value;
            $allocation->save();
        } elseif ($changeRequest->record_type === 'demand') {
            $demand = $changeRequest->demand;
            $demand->{$changeRequest->field} = $changeRequest->new_value;
            $demand->save();
        }

        // Update the change request status
        $changeRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approval_date' => Carbon::now(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChangeRequest $changeRequest): View
    {
        return view('change-request.show', compact('changeRequest'));
    }

    public function reject(ChangeRequest $changeRequest): RedirectResponse
    {
        $changeRequest->delete();

        return Redirect::route('change-requests.index')
            ->with('success', 'ChangeRequest deleted successfully');
    }
}
