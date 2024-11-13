<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AbilityRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $abilities = Ability::paginate();

        return view('ability.index', compact('abilities'))
            ->with('i', ($request->input('page', 1) - 1) * $abilities->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ability = new Ability();

        return view('ability.create', compact('ability'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AbilityRequest $request): RedirectResponse
    {
        Ability::create($request->validated());

        return Redirect::route('abilities.index')
            ->with('success', 'Ability created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $ability = Ability::find($id);

        return view('ability.show', compact('ability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $ability = Ability::find($id);

        return view('ability.edit', compact('ability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AbilityRequest $request, Ability $ability): RedirectResponse
    {
        $ability->update($request->validated());

        return Redirect::route('abilities.index')
            ->with('success', 'Ability updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Ability::find($id)->delete();

        return Redirect::route('abilities.index')
            ->with('success', 'Ability deleted successfully');
    }
}
