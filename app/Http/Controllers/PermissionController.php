<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct() // TODO change once perms seeded
    {
        // $this->middleware('permission:view', ['only' => ['index']]);
        // $this->middleware('permission:create', ['only' => ['create','store']]);
        // $this->middleware('permission:update', ['only' => ['update','edit']]);
        // $this->middleware('permission:delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $permissions = Permission::orderBy('name')->paginate();

        return view('permission.index', compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * $permissions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $permission = new Permission;

        return view('permission.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name',
            ],
        ]);

        Permission::create([
            'name' => $request->name,
        ]);
        // public function store(PermissionRequest $request): RedirectResponse
        // {
        //     Permission::create($request->validated());

        return Redirect::route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $permission = Permission::find($id);

        return view('permission.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $permission = Permission::find($id);

        return view('permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,'.$permission->id,
            ],
        ]);

        $permission->update([
            'name' => $request->name,
        ]);
        // public function update(PermissionRequest $request, Permission $permission): RedirectResponse
        // {
        //     $permission->update($request->validated());

        return Redirect::route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy($permissionId)
    {
        $permission = Permission::find($permissionId);
        $permission->delete();
        // public function destroy($id): RedirectResponse
        // {
        //     Permission::find($id)->delete();

        return Redirect::route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
