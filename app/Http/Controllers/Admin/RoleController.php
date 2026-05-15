<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private array $systemRoles = ['super_admin', 'editor', 'viewer'];

    public function index()
    {
        $roles = Role::withCount('permissions')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100|unique:roles,name',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name'       => $request->input('name'),
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->input('permissions', []));

        ActivityLog::record(
            "Role created: {$role->name}",
            'roles',
            ['id' => $role->id, 'permissions' => $request->input('permissions', [])]
        );

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' created successfully.");
    }

    public function edit($id)
    {
        $role        = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:100|unique:roles,name,' . $id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Prevent renaming system roles
        if (!in_array($role->name, $this->systemRoles)) {
            $role->name = $request->input('name');
            $role->save();
        }

        $role->syncPermissions($request->input('permissions', []));

        ActivityLog::record(
            "Role updated: {$role->name}",
            'roles',
            ['id' => $role->id, 'permissions' => $request->input('permissions', [])]
        );

        return back()->with('success', "Role '{$role->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, $this->systemRoles)) {
            return back()->with('error', "Cannot delete system role '{$role->name}'.");
        }

        ActivityLog::record(
            "Role deleted: {$role->name}",
            'roles',
            ['id' => $role->id]
        );

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
