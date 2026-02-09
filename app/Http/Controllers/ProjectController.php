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
     * Display a paginated, searchable list of projects.
     *
     * Supports an optional `search` query parameter to filter projects by empowerID, project name,
     * or related client name. The `perPage` input controls items per page (clamped between 1 and 100,
     * default 100).
     *
     * @return \Illuminate\View\View The project index view containing `projects` (paginated list) and `i` (page offset).
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
     * Display the project creation form.
     *
     * @return \Illuminate\View\View The creation view populated with an empty `Project` instance (`$project`) and a collection of `Client` records (`$clients`) ordered by name.
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
     * Display a project's details, its associated resources, and aggregated open demands.
     *
     * The view data includes:
     * - `project`: the Project model with related client and allocations.
     * - `resources`: unique Resource models referenced by the project's allocations; each resource has a boolean `current` flag indicating whether it has any allocation on or after the current month and a `resourceType_name` property with the related resource type's name.
     * - `demands`: aggregated open demands for the project grouped by resource type; each demand entry has `start` and `end` formatted as `Mon-Year` and `resource_type` replaced by the resource type's name, with `fte` representing the average FTE where FTE > 0.
     *
     * @param int $id The ID of the project to display.
     * @return \Illuminate\View\View The project show view populated with `project`, `resources`, and `demands`.
     */
    public function show($id): View
    {
        $project = Project::with(['client', 'allocations'])->find($id);
        
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
     * Display the edit form for a specified project.
     *
     * @param int $id The ID of the project to edit.
     * @return \Illuminate\View\View The project edit view populated with the `project` model and `clients` list.
     */
    public function edit($id): View
    {
        $project = Project::with('client')->find($id);
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

    /**
     * Delete the specified project by ID and redirect to the projects listing.
     *
     * @param int|string $id The ID of the project to delete.
     * @return \Illuminate\Http\RedirectResponse A redirect to the projects index route with a `success` flash message confirming deletion.
     */
    public function destroy($id): RedirectResponse
    {
        Project::find($id)->delete();

        return Redirect::route('projects.index')
            ->with('success', 'Project deleted successfully');
    }

}