<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $users = User::paginate();

        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();
        $teams = Team::all();

        return view('user.create', compact('user', 'teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        User::create($request->all());

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $teams = Team::all();

        return view('user.edit', compact('user', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Log::info("input: " . json_encode($request->all()));
        $user->update($request->all());
        $currentTeam = $user->currentTeam;
        $inputTeam = $request->input('team');

        if ($currentTeam !== $inputTeam) {
            $user->detachTeam($currentTeam);
        }

        if ($inputTeam) {
            $newTeam = Team::find($inputTeam);
            if ($newTeam) {
                $user->attachTeam($newTeam);
            }
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

    public function profile(): View
    {
        $user = auth()->user();

        return view('user.profile', compact('user'));
    }

    public function settings(): View
    {
        $user = auth()->user();

        return view('user.settings', compact('user'));
    }
}
