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

    public function update(Request $request)
    {
        $request->validate([
            'permissions' => 'array',
        ]);

        foreach ($request->permissions as $roleId => $permissionIds) {
            $role = Role::findOrFail($roleId);
            
            // Fetch permission names from IDs
            $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            
            // Assign permissions using names
            $role->syncPermissions($permissionNames);
        }

        return redirect()->route('permissions.index')->with('success', 'Permissions updated successfully.');
    }


    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
