<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:manage permissions');
    }

    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::all();
        return view('admin.permissions.index', compact('permissions', 'roles'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function getRolePermissions($roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $allPermissions = Permission::all();

        $groupedPermissions = $allPermissions->map(function ($permission) use ($role) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'group' => strtoupper($permission->group_name), // Group by first word
                'assigned' => $role->hasPermissionTo($permission->name),
            ];
        });

        return response()->json(['permissions' => $groupedPermissions], 200);
    }


    public function update(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
        ]);

        $role = Role::findOrFail($request->role_id);
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);

        return redirect()->route('permissions.index')->with('success', 'Permissions updated successfully.');
    }


    


    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
