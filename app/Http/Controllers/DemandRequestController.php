<?php

namespace App\Http\Controllers;

use App\Models\DemandRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DemandRequestRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DemandRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $demandRequests = DemandRequest::paginate();

        return view('demand-request.index', compact('demandRequests'))
            ->with('i', ($request->input('page', 1) - 1) * $demandRequests->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $demandRequest = new DemandRequest();

        return view('demand-request.create', compact('demandRequest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DemandRequestRequest $request): RedirectResponse
    {
        DemandRequest::create($request->validated());

        return Redirect::route('demand-requests.index')
            ->with('success', 'DemandRequest created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $demandRequest = DemandRequest::findOrFail($id);

        return view('demand-request.show', compact('demandRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $demandRequest = DemandRequest::findOrFail($id);

        return view('demand-request.edit', compact('demandRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandRequestRequest $request, DemandRequest $demandRequest): RedirectResponse
    {
        $demandRequest->update($request->validated());

        return Redirect::route('demand-requests.index')
            ->with('success', 'DemandRequest updated successfully');
    }

    public function destroy(DemandRequest $demandRequest): RedirectResponse
    {
        $demandRequest->delete();

        return Redirect::route('demand-requests.index')
            ->with('success', 'DemandRequest deleted successfully');
    }
}
