<?php

namespace App\Http\Controllers\Teamwork;

use App\Models\Team;
use App\Models\User;
use App\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mpociot\Teamwork\Exceptions\UserNotInTeamException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

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
        //TODO based on privs either show teams wher ethe user is a member, or show all teams

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
        $team = new Team();
        $users = User::all()->map(function ($user) {
            return ['value' => $user->id, 'name' => $user->name];
        })->toArray();
        $resource_types = ResourceType::all()->map(function ($resource_type) {
            return ['name' => $resource_type->name];
        })->toArray();
        return view('teamwork.create', compact('team', 'resource_types', 'users'));
        //return view('teamwork.create');
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
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $team = Team::with(['owner'])->find($id);
        $team->members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
            ->where('team_user.team_id', $team->id)
            ->get();
        $team->owner = User::find($team->owner_id);

        return view('teamwork.show', compact('team'));
    }

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
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        $team->members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
            ->where('team_user.team_id', $team->id)
            ->get();

        $users = User::all()->map(function ($user) {
            return ['value' => $user->id, 'name' => $user->name];
        })->toArray();

        $resource_types = ResourceType::all()->map(function ($resource_type) {
            return ['name' => $resource_type->name];
        })->toArray();

        if (!auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        //return view('teamwork.edit')->withTeam($team);
        return view('teamwork.edit', compact('team', 'users', 'resource_types'));
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
        if (!auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $team->delete();

        $userModel = config('teamwork.user_model');
        $userModel::where('current_team_id', $id)
            ->update(['current_team_id' => null]);

        return redirect(route('teams.index'));
    }


    /**
     * Make the given user the leader of the current team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function makeLeader($teamId, $userId)
    {
        Log::info("Team ID: $teamId, User ID: $userId");

        $team = Team::findOrFail($teamId);
        $user = User::findOrFail($userId);
        if (auth()->user()->isOwnerOfTeam($team)) {
            if ($team->hasUser($user)) {
                $team->owner_id = $user->id;
                $team->save();
            } else {
                return redirect(route('teams.index'))->with('error', 'User is not a member of the team');
            }
        } else {
            return redirect(route('teams.index'))->with('error', 'You are not the owner of this team');
        }

        return redirect(route('teams.show', $teamId))->with('success', 'Team leader updated successfully');
    }

}
