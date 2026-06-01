<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Module;
// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* =====================================
       USER LIST
    ===================================== */
    public function index()
    {
        $users = User::with('roles')->get();

        return view('users.index', compact('users'));
    }

    /* =====================================
       CREATE
    ===================================== */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /* =====================================
       STORE
    ===================================== */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:Users,email',
            'password' => 'required|min:4'
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Assign Role
            if ($request->role_id) {
                $user->roles()->sync([$request->role_id]);

                // Assign role permissions to user
                $permissions = RolePermission::where('role_id', $request->role_id)
                    ->pluck('permission_id')
                    ->toArray();

                if (!empty($permissions)) {
                    $user->permissions()->sync($permissions);
                }
            }

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'User created successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* =====================================
       EDIT
    ===================================== */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        $currentRoleId = $user->roles()->first()->id ?? null;

        return view('users.edit', compact('user', 'roles', 'currentRoleId'));
    }

    /* =====================================
       UPDATE
    ===================================== */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();

        try {

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Update Role
            if ($request->role_id) {
                $user->roles()->sync([$request->role_id]);

                // Update permissions from role
                $permissions = RolePermission::where('role_id', $request->role_id)
                    ->pluck('permission_id')
                    ->toArray();

                $user->permissions()->sync($permissions);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* =====================================
       DELETE
    ===================================== */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    /* =====================================
       USER PERMISSIONS PAGE
    ===================================== */
    public function permissions($id)
    {
        $user = User::findOrFail($id);

        $modules = Module::orderBy('parent_id')->get();
        $permissions = Permission::with('module')->get();

        // Build parent/child module hierarchy expected by the view.
        $moduleHierarchy = [];

        foreach ($modules as $module) {
            if (!$module->parent_id) {
                $moduleHierarchy[$module->id] = [
                    'id' => $module->id,
                    'name' => $module->name,
                    'children' => [],
                    'permissions' => [],
                ];
            }
        }

        foreach ($modules as $module) {
            if ($module->parent_id && isset($moduleHierarchy[$module->parent_id])) {
                $moduleHierarchy[$module->parent_id]['children'][$module->id] = [
                    'id' => $module->id,
                    'name' => $module->name,
                    'permissions' => [],
                ];
            }
        }

        foreach ($permissions as $permission) {
            if (!$permission->module) {
                continue;
            }

            $moduleId = $permission->module_id;
            $parentId = $permission->module->parent_id ?? 0;

            if ($parentId == 0 && isset($moduleHierarchy[$moduleId])) {
                $moduleHierarchy[$moduleId]['permissions'][] = $permission;
            } elseif (isset($moduleHierarchy[$parentId]['children'][$moduleId])) {
                $moduleHierarchy[$parentId]['children'][$moduleId]['permissions'][] = $permission;
            }
        }

        $assignedPermissions = $user->permissions()
            ->pluck('user_permissions.permission_id')
            ->toArray();

        return view('users.permissions', compact(
            'user',
            'moduleHierarchy',
            'assignedPermissions'
        ));
    }

    /* =====================================
       SAVE USER PERMISSIONS
    ===================================== */
    public function savePermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $permissionIds = $request->permissions ?? [];

        $user->permissions()->sync($permissionIds);

        return redirect()->route('users.index')
            ->with('success', 'User permissions updated successfully');
    }
}
