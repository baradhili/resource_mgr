<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\Resource;
use App\Models\Demand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $perPage = max(1, min((int) $request->input('perPage', 100), 100));

        $projects = Project::with('client')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('empowerID', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhereHas('client', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        });
                });
            })
            ->paginate($perPage);

        return view('project.index', compact('projects'))
            ->with('i', ($request->input('page', 1) - 1) * $projects->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $project = new Project;
        $clients = Client::orderBy('name')->get();

        return view('project.create', compact('project', 'clients'));
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
        $project = Project::with(['client', 'allocations'])->findOrFail($id);
        
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
        
        //get open demands for this project
        $demands = Demand::selectRaw('resource_type, MIN(demand_date) as start, MAX(demand_date) as end, AVG(fte) as fte')
            ->where('projects_id', $project->id)
            ->where('fte', '>', 0)
            ->groupBy('resource_type')
            ->get()
            ->map(function ($demand) {
                $demand->start = date('M-Y', strtotime($demand->start));
                $demand->end = date('M-Y', strtotime($demand->end));
                $demand->resource_type = \App\Models\ResourceType::find($demand->resource_type)->name;

                return $demand;
            });

        return view('project.show', compact('project', 'resources', 'demands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $project = Project::with('client')->findOrFail($id);
        $clients = Client::orderBy('name')->get();

        return view('project.edit', compact('project', 'clients'));
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