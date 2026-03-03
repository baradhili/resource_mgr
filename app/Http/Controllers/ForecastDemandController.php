<?php

namespace App\Http\Controllers;

use App\Models\ForecastDemand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ForecastDemandRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ForecastDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $forecastDemands = ForecastDemand::paginate();

        return view('forecast-demand.index', compact('forecastDemands'))
            ->with('i', ($request->input('page', 1) - 1) * $forecastDemands->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $forecastDemand = new ForecastDemand();

        return view('forecast-demand.create', compact('forecastDemand'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ForecastDemandRequest $request): RedirectResponse
    {
        ForecastDemand::create($request->validated());

        return Redirect::route('forecast-demands.index')
            ->with('success', 'ForecastDemand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $forecastDemand = ForecastDemand::findOrFail($id);

        return view('forecast-demand.show', compact('forecastDemand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $forecastDemand = ForecastDemand::findOrFail($id);

        return view('forecast-demand.edit', compact('forecastDemand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ForecastDemandRequest $request, ForecastDemand $forecastDemand): RedirectResponse
    {
        $forecastDemand->update($request->validated());

        return Redirect::route('forecast-demands.index')
            ->with('success', 'ForecastDemand updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ForecastDemand::find($id)->delete();

        return Redirect::route('forecast-demands.index')
            ->with('success', 'ForecastDemand deleted successfully');
    }
}

