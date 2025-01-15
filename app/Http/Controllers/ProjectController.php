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
    public function __construct()
    {
        //do the auth stuff here
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $projects = Project::paginate();

        return view('project.index', compact('projects'))
            ->with('i', ($request->input('page', 1) - 1) * $projects->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $project = new Project();

        if ($request->has('name')) {
            $project->name = $request->query('name');
        }

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
        $project = Project::find($id);
        // $allocations = Allocation::where('projects_id', $id)->get();
        $resources = Resource::whereHas('allocations', function ($query) use ($id) {
            $query->where('projects_id', $id);
        })->with('resourceType')->get();
Log::info(json_encode($resources, JSON_PRETTY_PRINT));
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

    public function search(Request $request): View
    {
        $search = $request->input('search');

        $projects = Project::where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('empowerID', 'like', "%{$search}%");
        })->paginate();

        return view('project.index', compact('projects'))
            ->with('i', ($request->input('page', 1) - 1) * $projects->perPage());
    }
}
