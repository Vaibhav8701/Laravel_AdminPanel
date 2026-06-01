<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Module;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Roles
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Role Form
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('roles.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Role
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Set Permissions
    |--------------------------------------------------------------------------
    */
    public function setPermission($id)
    {
        $role = Role::findOrFail($id);

        $modules = Module::orderBy('parent_id')->get();
        $permissions = Permission::with('module')->get();
        
        // Build hierarchy
        $moduleHierarchy = [];

        foreach ($modules as $module) {
            if (!$module->parent_id) {
                $moduleHierarchy[$module->id] = [
                    'id' => $module->id,
                    'name' => $module->name,
                    'children' => [],
                    'permissions' => []
                ];
            }
        }

        // Attach children
        foreach ($modules as $module) {
            if ($module->parent_id && isset($moduleHierarchy[$module->parent_id])) {
                $moduleHierarchy[$module->parent_id]['children'][$module->id] = [
                    'id' => $module->id,
                    'name' => $module->name,
                    'permissions' => []
                ];
            }
        }

        // Attach permissions
        foreach ($permissions as $permission) {
            $moduleId = $permission->module_id;
            $parentId = $permission->module->parent_id ?? 0;

            if ($parentId == 0) {
                $moduleHierarchy[$moduleId]['permissions'][] = $permission;
            } else {
                $moduleHierarchy[$parentId]['children'][$moduleId]['permissions'][] = $permission;
            }
        }

        $assignedPermissions = RolePermission::where('role_id', $id)
            ->pluck('permission_id')
            ->toArray();

        return view('roles.setpermission', compact(
            'role',
            'moduleHierarchy',
            'assignedPermissions'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Save Permissions
    |--------------------------------------------------------------------------
    */
    public function savePermissions(Request $request, $id)
    {
        $permissionIds = $request->input('permissions', []);

        DB::transaction(function () use ($id, $permissionIds) {
            RolePermission::where('role_id', $id)->delete();

            foreach ($permissionIds as $pid) {
                RolePermission::create([
                    'role_id' => $id,
                    'permission_id' => $pid
                ]);
            }
        });

        return redirect()->route('roles.index')->with('success', 'Permissions updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Role
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Role
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Role
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        Role::destroy($id);

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
