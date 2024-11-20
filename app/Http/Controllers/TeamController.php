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

        $users = User::all();
 
        return view('team.edit', compact('team', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamRequest $request, Team $team): RedirectResponse
    {

        $team->update($request->validated());

        $existing_members = User::join('team_user', 'users.id', '=', 'team_user.user_id')
            ->where('team_user.team_id', $team->id)
            ->pluck('id')
            ->toArray();

        $members = [];
        $members_data = json_decode($request->input('members'), true);
        foreach ($members_data as $member_data) {
            $member_name = $member_data['value'];

            $member = User::where('name', $member_name)->pluck('id')->first();
            if ($member) {
                Log::info("member: " . print_r($member, true));
                \App\Models\TeamUser::firstOrCreate([
                    'team_id' => $team->id,
                    'user_id' => $member,
                ]);
                $members[] = $member;
            }
        }
        $members_to_remove = array_diff($existing_members, $members);
        foreach ($members_to_remove as $member_id) {
            TeamUser::where('team_id', $team->id)->where('user_id', $member_id)->delete();
        }


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
