<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\Module;
class MenuController extends Controller
{
    
    public function index()
    {
        // $permissionModel = new \App\Models\PermissionModel();
        // $data['permissions'] = $permissionModel->findAll();

        return view('menus.index');

    }

    public function load()
    {
     
        $jsonFile = resource_path('views/menus/menu.json');

        // File not found
        if (!file_exists($jsonFile)) {
            return response()->json([]);
        }

        // Read file
        $raw = file_get_contents($jsonFile);

        // Decode JSON
        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            return response()->json([]);
        }

        // Format JSON (pretty print)
        $formatted = json_encode(
            $decoded,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

        try {
            // Clear table
            DB::table('menus')->truncate();

            // Insert new menu
            Menu::create([
                'name' => 'menu-editor',
                'is_active' => 1,
                'full_json' => $formatted,
            ]);
        } catch (\Throwable $e) {
            // Optional: log error
            // \Log::error($e->getMessage());
        }

        // Return decoded JSON
        return response()->json($decoded);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function save(Request $request)
    {
        try {

            // Get JSON from form data (sent as 'json' parameter)
            $jsonData = $request->input('json');
    
            if (empty($jsonData)) {
                return $this->jsonResponse(false, 'No data received');
            }

            $decoded = json_decode($jsonData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->jsonResponse(false, 'Invalid JSON: ' . json_last_error_msg());
            }

            // Pretty format JSON
            $formattedJson = json_encode(
                $decoded,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );

            // Save to JSON file
            $jsonFile = resource_path('views/menus/menu.json');

            if (file_put_contents($jsonFile, $formattedJson) === false) {
                return $this->jsonResponse(false, 'Failed to save file');
            }

            // Save to DB
            DB::table('menus')->truncate();

            Menu::create([
                'name'      => 'menu-editor',
                'is_active' => 1,
                'full_json' => $formattedJson
            ]);

            // Save modules recursively
            $this->module($decoded);

            return $this->jsonResponse(true, 'Menu saved successfully');

        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage());
        }
    }

    /**
     * Recursive Module Insert
     */
    public function module($decoded, $parentModuleId = 0)
    {
        // Only truncate on first call
        if ($parentModuleId === 0) {
            DB::table('modules')->truncate();
        }

        foreach ($decoded as $item) {

            $module = Module::create([
                'parent_id'    => $parentModuleId,
                'name'         => $item['text'] ?? null,
                'permission'   => $item['permission'] ?? null,
                'deletestatus' => (int) ($item['deletestatus'] ?? 1),
                'is_active'    => (int) ($item['is_active'] ?? 1),
            ]);

            // If children exist → call recursively
            if (!empty($item['children']) && is_array($item['children'])) {
                $this->module($item['children'], $module->id);
            }
        }
    }

    /**
     * JSON Response Helper
     */
    private function jsonResponse($success, $message)
    {
        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}

