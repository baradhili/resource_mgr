<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Apply middleware to the controller.
     *
     * @return void
     */
    public function __construct() // change this later once perms seeded
    {
        //     $this->middleware('role:view', ['only' => ['index']]);
        //     $this->middleware('role:create', ['only' => ['create', 'store', 'addPermissionToRole', 'givePermissionToRole']]);
        //     $this->middleware('role:update', ['only' => ['update', 'edit']]);
        //     $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $roles = Role::paginate();

        return view('role.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $role = new Role;
        $permissions = Permission::all();
        $role_permissions = collect();

        return view('role.create', compact('role', 'role_permissions', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse // RoleRequest
    {
        // Role::create($request->validated());
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name',
            ],
            'role_permissions' => 'nullable|string',
        ]);

        // Retrieve the permission objects based on the names
        $permissions_names = explode(',', $request->role_permissions);
        $permissions = Permission::whereIn('name', $permissions_names)->get();

        $role = Role::create([
            'name' => $request->name,
        ]);

        // Synchronize the permissions with the role
        $role->syncPermissions($permissions);

        return Redirect::route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $role = Role::findById($id);
        $role_permissions = $role->permissions;

        return view('role.show', compact('role', 'role_permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $role = Role::findById($id);
        $role_permissions = $role->permissions;
        $permissions = Permission::all();

        return view('role.edit', compact('role', 'role_permissions', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        // $role->update($request->validated());
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,'.$role->id,
            ],
            'role_permissions' => 'nullable|string',
        ]);

        $role = Role::findById($role->id);
        // Retrieve the permission objects based on the names
        $permissions_names = explode(',', $request->role_permissions);
        $permissions = Permission::whereIn('name', $permissions_names)->get();

        $role->update([
            'name' => $request->name,
        ]);

        // Synchronize the permissions with the role
        $role->syncPermissions($permissions);

        return Redirect::route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    // public function destroy($id): RedirectResponse
    // {
    //     Role::find($id)->delete();

    //     return Redirect::route('roles.index')
    //         ->with('success', 'Role deleted successfully');
    // }
    public function destroy($roleId)
    {
        $role = Role::find($roleId);
        $role->delete();

        return redirect('roles')->with('status', 'Role Deleted Successfully');
    }

    public function addPermissionToRole($roleId)
    {
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('role-permission.role.add-permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId)
    {
        $request->validate([
            'permission' => 'required',
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);

        return redirect()->back()->with('status', 'Permissions added to role');
    }
}
