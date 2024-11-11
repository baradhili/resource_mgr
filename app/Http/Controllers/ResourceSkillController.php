<?php

namespace App\Http\Controllers;

use App\Models\ResourceSkill;
use Illuminate\Http\Request;
use App\Http\Requests\ResourceSkillRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceSkillResource;

class ResourceSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $resourceSkills = ResourceSkill::paginate();

        return ResourceSkillResource::collection($resourceSkills);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResourceSkillRequest $request): ResourceSkill
    {
        return ResourceSkill::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(ResourceSkill $resourceSkill): ResourceSkill
    {
        return $resourceSkill;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceSkillRequest $request, ResourceSkill $resourceSkill): ResourceSkill
    {
        $resourceSkill->update($request->validated());

        return $resourceSkill;
    }

    public function destroy(ResourceSkill $resourceSkill): Response
    {
        $resourceSkill->delete();

        return response()->noContent();
    }
}
