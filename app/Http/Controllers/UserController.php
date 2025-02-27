<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Resource;
use App\Models\Location;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $users = User::with('reportingLine')->paginate();

        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();
        $users = User::all();
        $teams = Team::all();
        $resources = Resource::all();
        return view('user.create', compact('user', 'users', 'teams', 'resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);
        $reportees = $user->reportees; // Get the people who report to this user

        return view('user.show', compact('user', 'reportees'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $users = User::all();
        $teams = Team::all();
        $resources = Resource::all();
        return view('user.edit', compact('user', 'users', 'teams', 'resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $current_team = $user->currentTeam;
        $user->update($request->validated());
        //Make sure resource types are synced and or updated
        //get resource team/type
        $team = Team::find($request->validated()['current_team_id']);
        $resource = Resource::find($request->validated()['resource_id']);

        if ($team->id !== $current_team->id) {
            $user->attachTeam($team);
            // $user->teams()->attach($team->id);
            $user->detachTeam($current_team);
            // $user->teams()->detach($current_team->id);
        }
        if (is_null($resource->resource_type) || $resource->resource_type !== $user->currentTeam->resource_type) {
            $resource->resource_type = $user->currentTeam->resource_type;
        }


        return Redirect::route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Display the specified resource.
     */
    public function profile($id): View
    {
        $user = User::find($id);
        $reportees = $user->reportees; // Get the people who report to this user
        $reports = $user->reports;
        $resource = $user->resource;
        $regionObj = Region::find($resource->region_id);
        $region = $regionObj ? $regionObj->name : 'Unknown Region';
        $locationObj = Location::find($resource->location_id);
        $location = $locationObj ? $locationObj->name : 'Unknown Location';
        $skills = $resource->skills;
Log::info('User Profile Data:', [
    'user' => json_encode($user),
    'reportees' => json_encode($reportees),
    'region' => json_encode($region),
    'location' => json_encode($location),
    'reports' => json_encode($reports),
    'resource' => json_encode($resource),
    'skills' => json_encode($skills),
]);


        return view('user.profile', compact('user', 'reportees','region','location', 'reports', 'resource','skills'));
    }
}
