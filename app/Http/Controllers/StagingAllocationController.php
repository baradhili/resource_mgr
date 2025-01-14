<?php

namespace App\Http\Controllers;

use App\Models\StagingAllocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StagingAllocationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class StagingAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $stagingAllocations = StagingAllocation::paginate();

        return view('staging-allocation.index', compact('stagingAllocations'))
            ->with('i', ($request->input('page', 1) - 1) * $stagingAllocations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stagingAllocation = new StagingAllocation();

        return view('staging-allocation.create', compact('stagingAllocation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StagingAllocationRequest $request): RedirectResponse
    {
        StagingAllocation::create($request->validated());

        return Redirect::route('staging-allocations.index')
            ->with('success', 'StagingAllocation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $stagingAllocation = StagingAllocation::find($id);

        return view('staging-allocation.show', compact('stagingAllocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stagingAllocation = StagingAllocation::find($id);

        return view('staging-allocation.edit', compact('stagingAllocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StagingAllocationRequest $request, StagingAllocation $stagingAllocation): RedirectResponse
    {
        $stagingAllocation->update($request->validated());

        return Redirect::route('staging-allocations.index')
            ->with('success', 'StagingAllocation updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        StagingAllocation::find($id)->delete();

        return Redirect::route('staging-allocations.index')
            ->with('success', 'StagingAllocation deleted successfully');
    }
}
