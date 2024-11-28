<?php

namespace App\Http\Controllers\Api;

use App\Models\ProjectRegion;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRegionRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectRegionResource;

class ProjectRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projectRegions = ProjectRegion::paginate();

        return ProjectRegionResource::collection($projectRegions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRegionRequest $request): ProjectRegion
    {
        return ProjectRegion::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectRegion $projectRegion): ProjectRegion
    {
        return $projectRegion;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRegionRequest $request, ProjectRegion $projectRegion): ProjectRegion
    {
        $projectRegion->update($request->validated());

        return $projectRegion;
    }

    public function destroy(ProjectRegion $projectRegion): Response
    {
        $projectRegion->delete();

        return response()->noContent();
    }
}
