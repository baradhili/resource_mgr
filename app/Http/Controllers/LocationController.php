<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LocationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $locations = Location::with('region')->paginate();

        return view('location.index', compact('locations'))
            ->with('i', ($request->input('page', 1) - 1) * $locations->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $location = new Location();
        $regions = Region::all();

        return view('location.create', compact('location', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request): RedirectResponse
    {
        Location::create($request->validated());

        return Redirect::route('locations.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $location = Location::with('region')->find($id);

        return view('location.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $location = Location::with('region')->find($id);
        $regions = Region::all();

        return view('location.edit', compact('location', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, Location $location): RedirectResponse
    {
        $location->update($request->validated());

        return Redirect::route('locations.index')
            ->with('success', 'Location updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Location::find($id)->delete();

        return Redirect::route('locations.index')
            ->with('success', 'Location deleted successfully');
    }
}
