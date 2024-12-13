<?php

namespace App\Http\Controllers\Teamwork;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * old
         *  $teams = Team::with('owner')->paginate();
         *         return view('team.index', compact('teams'))
         *             ->with('i', ($request->input('page', 1) - 1) * $teams->perPage());
         */
        return view('teamwork.index')
            ->with('teams', auth()->user()->teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // old
        // $resource_types = ResourceType::all()->map(function ($resource_type) {
        //     return ['name' => $resource_type->name];
        // })->toArray();
        // return view('team.create', compact('team', 'resource_types'));
        return view('teamwork.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $teamModel = config('teamwork.team_model');

        $team = $teamModel::create([
            'name' => $request->name,
            'owner_id' => $request->user()->getKey(),
        ]);
        $request->user()->attachTeam($team);

        return redirect(route('teams.index'));
    }

     /**
     * Display the specified resource.
     */
    // public function show($id): View
    // {
    //     $team = Team::with(['owner'])->find($id);
    //     $team->members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
    //         ->where('team_user.team_id', $team->id)
    //         ->get();

    //     return view('team.show', compact('team'));
    // }

    /**
     * Switch to the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function switchTeam($id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);
        try {
            auth()->user()->switchTeam($team);
        } catch (UserNotInTeamException $e) {
            abort(403);
        }

        return redirect(route('teams.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $team->members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
        //     ->where('team_user.team_id', $team->id)
        //     ->get();

        // $users = User::all()->map(function ($user) {
        //     return ['value' => $user->id, 'name' => $user->name];
        // })->toArray();

        // $resource_types = ResourceType::all()->map(function ($resource_type) {
        //     return ['name' => $resource_type->name];
        // })->toArray();

        // return view('team.edit', compact('team', 'users', 'resource_types'));
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        if (! auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        return view('teamwork.edit')->withTeam($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $team->update($request->validated());

        // $existing_members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
        //     ->where('team_user.team_id', $team->id)
        //     ->pluck('id')
        //     ->toArray();

        // $members = [];
        // $members_data = json_decode($request->input('members'), true);
        // $members = array_column($members_data, 'value');
        // $team->users()->sync($members);

        // return Redirect::route('teams.index')
        //     ->with('success', 'Team updated successfully');
        $request->validate([
            'name' => 'required|string',
        ]);

        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        $team->name = $request->name;
        $team->save();

        return redirect(route('teams.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teamModel = config('teamwork.team_model');

        $team = $teamModel::findOrFail($id);
        if (! auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $team->delete();

        $userModel = config('teamwork.user_model');
        $userModel::where('current_team_id', $id)
                    ->update(['current_team_id' => null]);

        return redirect(route('teams.index'));
    }
}
