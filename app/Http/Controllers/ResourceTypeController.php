<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceTypeRequest;
use App\Models\ResourceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ResourceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $resourceTypes = ResourceType::paginate();

        return view('resource-type.index', compact('resourceTypes'))
            ->with('i', ($request->input('page', 1) - 1) * $resourceTypes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $resourceType = new ResourceType;

        return view('resource-type.create', compact('resourceType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResourceTypeRequest $request): RedirectResponse
    {
        ResourceType::create($request->validated());

        return Redirect::route('resource-types.index')
            ->with('success', 'ResourceType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $resourceType = ResourceType::find($id);

        return view('resource-type.show', compact('resourceType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $resourceType = ResourceType::find($id);

        return view('resource-type.edit', compact('resourceType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceTypeRequest $request, ResourceType $resourceType): RedirectResponse
    {
        $resourceType->update($request->validated());

        return Redirect::route('resource-types.index')
            ->with('success', 'ResourceType updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ResourceType::find($id)->delete();

        return Redirect::route('resource-types.index')
            ->with('success', 'ResourceType deleted successfully');
    }
}
