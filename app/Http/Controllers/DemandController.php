<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DemandRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $demands = Demand::paginate();

        return view('demand.index', compact('demands'))
            ->with('i', ($request->input('page', 1) - 1) * $demands->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $demand = new Demand();

        return view('demand.create', compact('demand'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DemandRequest $request): RedirectResponse
    {
        Demand::create($request->validated());

        return Redirect::route('demands.index')
            ->with('success', 'Demand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $demand = Demand::find($id);

        return view('demand.show', compact('demand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $demand = Demand::find($id);

        return view('demand.edit', compact('demand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandRequest $request, Demand $demand): RedirectResponse
    {
        $demand->update($request->validated());

        return Redirect::route('demands.index')
            ->with('success', 'Demand updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Demand::find($id)->delete();

        return Redirect::route('demands.index')
            ->with('success', 'Demand deleted successfully');
    }
}
