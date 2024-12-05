<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\TeamUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // $teams = Team::paginate();
        $teams = Team::with('owner')->paginate();
Log::info("teams :".json_encode($teams));
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
        $team = Team::with(['owner'])->find($id);
        $team->members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
            ->where('team_user.team_id', $team->id)
            ->get();

        return view('team.show', compact('team'));
    }

    /** 
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $team = Team::with(['owner'])->find($id);

        $team->members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
            ->where('team_user.team_id', $team->id)
            ->get();

        $users = User::all()->map(function ($user) {
            return ['value' => $user->id, 'name' => $user->name];
        })->toArray();
        
        return view('team.edit', compact('team', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamRequest $request, Team $team): RedirectResponse
    {

        Log::info("input: ".json_encode($request->all()));
        $team->update($request->validated());

        $existing_members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
            ->where('team_user.team_id', $team->id)
            ->pluck('id')
            ->toArray();

        $members = [];
        $members_data = json_decode($request->input('members'), true);
        $members = array_column($members_data, 'value');
        $team->users()->sync($members);

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
