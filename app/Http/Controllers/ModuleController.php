<?php

namespace App\Http\Controllers;
use App\Models\Module;
use App\Models\Permission;

use Illuminate\Http\Request;

class ModuleController extends Controller
{

    public function index()
    {
        $modules = Module::orderBy('id', 'ASC')->get();

        return view('modules.index', compact('modules'));

    }

    public function savePermission(Request $request)
    {
        $moduleId = $request->input('module_id');
        $permissions = $request->input('permissions');

        if (empty($moduleId) || empty($permissions) || !is_array($permissions)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data'
            ]);
        }

        // Delete old permissions
        Permission::where('module_id', $moduleId)->delete();

        // Insert new permissions
        foreach ($permissions as $permission) {
            Permission::create([
                'module_id' => $moduleId,
                'permission_name' => $permission
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Permissions
    |--------------------------------------------------------------------------
    */
    public function getPermissions(Request $request, $moduleId = null)
    {
        // Prefer GET parameter if exists
        $fromGet = $request->query('module_id');

        if (!empty($fromGet)) {
            $moduleId = (int) $fromGet;
        }

        $moduleId = (int) ($moduleId ?? 0);

        if ($moduleId <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid module id',
                'permissions' => []
            ]);
        }

        $rows = Permission::where('module_id', $moduleId)
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json([
            'success' => true,
            'permissions' => $rows
        ]);
    }
}

