<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $teams = Team::paginate();

        return view('team.index', compact('teams'))
            ->with('i', ($request->input('page', 1) - 1) * $teams->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $team = new Team();

        return view('team.create', compact('team'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamRequest $request): RedirectResponse
    {
        Team::create($request->validated());

        return Redirect::route('teams.index')
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $team = Team::find($id);

        return view('team.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $team = Team::find($id);

        return view('team.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamRequest $request, Team $team): RedirectResponse
    {
        $team->update($request->validated());

        return Redirect::route('teams.index')
            ->with('success', 'Team updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Team::find($id)->delete();

        return Redirect::route('teams.index')
            ->with('success', 'Team deleted successfully');
    }
}
