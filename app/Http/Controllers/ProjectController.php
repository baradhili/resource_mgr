<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Allocation;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $projects = Project::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('empowerID', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
            });
        })->paginate();

        return view('project.index', compact('projects'))
            ->with('i', ($request->input('page', 1) - 1) * $projects->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $project = new Project();

        return view('project.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request): RedirectResponse
    {
        Project::create($request->validated());

        return Redirect::route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $project = Project::with('allocations')->find($id);
        $resources = $project->allocations
            ->pluck('resources_id')
            ->unique()
            ->transform(function ($resourceId) use ($project) {
                $resource = Resource::find($resourceId);
                $resource->current = $project->allocations
                    ->where('resources_id', $resourceId)
                    ->where('allocation_date', '>=', now()->startOfMonth())
                    ->count() > 0;
                $resource->resourceType_name = $resource->resourceType->name;
                return $resource;
            });
            Log::info("resources: " . json_encode($resources));
        return view('project.show', compact('project', 'resources'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $project = Project::find($id);

        return view('project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return Redirect::route('projects.index')
            ->with('success', 'Project updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Project::find($id)->delete();

        return Redirect::route('projects.index')
            ->with('success', 'Project deleted successfully');
    }
}
