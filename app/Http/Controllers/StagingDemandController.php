<?php

namespace App\Http\Controllers;

use App\Models\StagingDemand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StagingDemandRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class StagingDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $stagingDemands = StagingDemand::paginate();

        return view('staging-demand.index', compact('stagingDemands'))
            ->with('i', ($request->input('page', 1) - 1) * $stagingDemands->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stagingDemand = new StagingDemand();

        return view('staging-demand.create', compact('stagingDemand'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StagingDemandRequest $request): RedirectResponse
    {
        StagingDemand::create($request->validated());

        return Redirect::route('staging-demands.index')
            ->with('success', 'StagingDemand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $stagingDemand = StagingDemand::find($id);

        return view('staging-demand.show', compact('stagingDemand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stagingDemand = StagingDemand::find($id);

        return view('staging-demand.edit', compact('stagingDemand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StagingDemandRequest $request, StagingDemand $stagingDemand): RedirectResponse
    {
        $stagingDemand->update($request->validated());

        return Redirect::route('staging-demands.index')
            ->with('success', 'StagingDemand updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        StagingDemand::find($id)->delete();

        return Redirect::route('staging-demands.index')
            ->with('success', 'StagingDemand deleted successfully');
    }
}
