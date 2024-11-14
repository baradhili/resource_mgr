<?php

namespace App\Http\Controllers;

use App\Models\Capability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CapabilityRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CapabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $capabilities = Capability::paginate();

        return view('capability.index', compact('capabilities'))
            ->with('i', ($request->input('page', 1) - 1) * $capabilities->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $capability = new Capability();

        return view('capability.create', compact('capability'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CapabilityRequest $request): RedirectResponse
    {
        Capability::create($request->validated());

        return Redirect::route('capabilities.index')
            ->with('success', 'Capability created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $capability = Capability::find($id);

        return view('capability.show', compact('capability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $capability = Capability::find($id);

        return view('capability.edit', compact('capability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CapabilityRequest $request, Capability $capability): RedirectResponse
    {
        $capability->update($request->validated());

        return Redirect::route('capabilities.index')
            ->with('success', 'Capability updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Capability::find($id)->delete();

        return Redirect::route('capabilities.index')
            ->with('success', 'Capability deleted successfully');
    }
}
