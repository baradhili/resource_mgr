<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Resource;
use App\Models\Location;
use App\Models\Region;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $usersQuery = User::with('reportingLine')
            ->get()
            ->sortBy(function ($user) {
                return $user->reportingLine ? $user->reportingLine->name : '';
            })
            ->values();

        // Get the current page from the request
        $page = $request->input('page', 1);
        $perPage = 10;
        $users = new LengthAwarePaginator(
            $usersQuery->forPage($request->input('page', 1), $perPage),
            $usersQuery->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('user.index', compact('users'))
            ->with('i', $users->currentPage() * $users->perPage() - $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();
        $users = User::all();
        $teams = Team::all();
        $roles = Role::all();
        $resources = Resource::all();
        return view('user.create', compact('user', 'users', 'teams', 'resources','roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = $data['password'] ?? bcrypt('password');
        if (User::where('email', $data['email'])->count() > 0) {
            return Redirect::route('users.index')
                ->with('error', 'Email already exists.');
        }
        User::create($data);

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::with('roles')->find($id);
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
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles', 'users', 'teams', 'resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $current_team = $user->currentTeam;
        $roles = $request->input('roles');
        $user->update($request->validated());
        if ($user->save() === false) {
            Log::info('Error updating user: ' . json_encode($request->all()));
            exit;
        }
        
        //Make sure resource types are synced and or updated
        //get resource team/type
        $team = Team::find($request->validated()['current_team_id']);
        $resource = Resource::find($request->validated()['resource_id']);

        if (is_null($current_team) || $team->id !== $current_team->id) {
            // user is not a member of a team, so attach
            $user->attachTeam($team);
            // $user->teams()->attach($team->id);
        } else {
            // if user is a member of a team, detach old team and attach new team
            $user->detachTeam($current_team);
            // $user->teams()->detach($current_team->id);
            $user->attachTeam($team);
            // $user->teams()->attach($team->id);
        }
        if (!is_null($resource)) {
            if (is_null($resource->resource_type) || $resource->resource_type !== $user->currentTeam->resource_type) {
                $resource->resource_type = $user->currentTeam->resource_type;
            }
        }

        //sync using spatie/permissions calls
        $user->syncRoles($roles);

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

        // Modify resource names to add [c] if the resource is not permanent
        foreach ($reportees as $reportee) {
            if ($reportee->resource && isset($reportee->resource->contracts[0]) && !$reportee->resource->contracts[0]->permanent) {
                $reportee->name .= ' [c]';
            }
        }
        $reports = $user->reports;
        $resource = $user->resource;
        $userRoles = $user->roles;
        $regionObj = $resource ? Region::find($resource->region_id) : null;
        $region = $regionObj ? $regionObj->name : 'Unknown Region';
        $locationObj = $resource ? Location::find($resource->location_id) : null;
        $location = $locationObj ? $locationObj->name : 'Unknown Location';
        $skills = $resource ? $resource->skills : [(object) ['skill_name' => 'Unknown Skills']];
        // Log::info("roles available: " . json_encode($roles));
        // Log::info("current roles" . json_encode($userRoles));


        return view('user.profile', compact('user', 'userRoles', 'reportees', 'region', 'location', 'reports', 'resource', 'skills'));
    }
}
