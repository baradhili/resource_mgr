<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SkillRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $skills = Skill::paginate();

        return view('skill.index', compact('skills'))
            ->with('i', ($request->input('page', 1) - 1) * $skills->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $skill = new Skill();

        return view('skill.create', compact('skill'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['sfia_level'] = (int) $validated['sfia_level'];

        Skill::create($validated);

        return Redirect::route('skills.index')
            ->with('success', 'Skill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $skill = Skill::find($id);

        return view('skill.show', compact('skill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $skill = Skill::find($id);

        return view('skill.edit', compact('skill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SkillRequest $request, Skill $skill): RedirectResponse
    {
        $skill->update($request->validated());

        return Redirect::route('skills.index')
            ->with('success', 'Skill updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Skill::find($id)->delete();

        return Redirect::route('skills.index')
            ->with('success', 'Skill deleted successfully');
    }
}
