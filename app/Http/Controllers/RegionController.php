<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RegionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $regions = Region::paginate();

        return view('region.index', compact('regions'))
            ->with('i', ($request->input('page', 1) - 1) * $regions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $region = new Region();

        return view('region.create', compact('region'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegionRequest $request): RedirectResponse
    {
        Region::create($request->validated());

        return Redirect::route('regions.index')
            ->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $region = Region::find($id);

        return view('region.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $region = Region::find($id);

        return view('region.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegionRequest $request, Region $region): RedirectResponse
    {
        $region->update($request->validated());

        return Redirect::route('regions.index')
            ->with('success', 'Region updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Region::find($id)->delete();

        return Redirect::route('regions.index')
            ->with('success', 'Region deleted successfully');
    }
}
