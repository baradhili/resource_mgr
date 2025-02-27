<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Resource;
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
        return view('user.create', compact('user','users', 'teams', 'resources'));
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
Log::info("Reportees: ". json_encode($reportees));
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
        return view('user.edit', compact('user','users','teams','resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return Redirect::route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
