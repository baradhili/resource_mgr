<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceSkillRequest;
use App\Models\Contract;
use App\Models\Resource;
use App\Models\ResourceSkill;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ResourceSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $resourceSkills = ResourceSkill::paginate($request->input('perPage', 10));

        return view('resource-skill.index', compact('resourceSkills'))
            ->with('i', ($request->input('page', 1) - 1) * $resourceSkills->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $resourceId = $request->query('id');
        $resource = null;
        if ($resourceId) {
            $resource = Resource::findOrFail($resourceId);
            // Log::info("resource id = " . $resource->full_name);

            $skills = ResourceSkill::where('resources_id', $resource->id)->get();

            $allSkills = Skill::all();
            $unassignedSkills = $allSkills->diff($skills);
            // Log::info("unassigned skills = " . print_r($unassignedSkills, true));
            $resources = null;

        } else {
            $resource = new Resource;
            $resource->id = 0;

            $currentContracts = Contract::where('end_date', '>=', Carbon::today())->pluck('resources_id');
            $resources = Resource::whereIn('id', $currentContracts)->get();

            $allSkills = Skill::all();
            $unassignedSkills = $allSkills;
        }

        $resourceSkill = new ResourceSkill;

        return view('resource-skill.create', compact('resourceSkill', 'resource', 'unassignedSkills', 'resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResourceSkillRequest $request): RedirectResponse
    {
        ResourceSkill::create($request->validated());

        return Redirect::route('resource-skills.index')
            ->with('success', 'ResourceSkill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $resourceSkill = ResourceSkill::find($id);

        return view('resource-skill.show', compact('resourceSkill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $resourceSkill = ResourceSkill::find($id);

        return view('resource-skill.edit', compact('resourceSkill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceSkillRequest $request, ResourceSkill $resourceSkill): RedirectResponse
    {
        $resourceSkill->update($request->validated());

        return Redirect::route('resource-skills.index')
            ->with('success', 'ResourceSkill updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ResourceSkill::find($id)->delete();

        return Redirect::route('resource-skills.index')
            ->with('success', 'ResourceSkill deleted successfully');
    }
}
