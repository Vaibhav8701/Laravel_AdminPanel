<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('getMenus')) {
    function getMenus(): array
    {
        // Get active menu record
        $menuRecord = DB::table('menus')                                                            
            ->where('is_active', 1)
            ->first();

        $menuJson = getMenuJson($menuRecord);
       
        if (empty($menuJson)) {
            return [];
        }

        $menus = json_decode($menuJson, true);
       
        return is_array($menus)
            ? array_map('convertMenuFormat', $menus)
            : [];
    }
}

/*
|--------------------------------------------------------------------------
| Get Menu JSON (DB first, fallback to file)
|--------------------------------------------------------------------------
*/
if (!function_exists('getMenuJson')) {
    function getMenuJson($menuRecord): string
    {
        if ($menuRecord && !in_array($menuRecord->full_json, [null, 'null', ''], true)) {
            return $menuRecord->full_json;
        }

        // fallback JSON file
        $jsonFile = resource_path('views/menus/menu.json');

        return file_exists($jsonFile)
            ? file_get_contents($jsonFile)
            : '';
    }
}

/*
|--------------------------------------------------------------------------
| Convert Menu Format 
|--------------------------------------------------------------------------
*/
if (!function_exists('convertMenuFormat')) {
    function convertMenuFormat(array $menu): array
    {
        $formatted = [
            'id'        => $menu['moduleid'] ?? 0,
            'name'      => $menu['text'] ?? '',
            'url'       => $menu['href'] ?? '',
            'icon'      => $menu['icon'] ?? '',
            'is_active' => $menu['is_active'] ?? 1,
            'children'  => [],
        ];

        if (!empty($menu['children']) && is_array($menu['children'])) {
            $formatted['children'] = array_map('convertMenuFormat', $menu['children']);
        }

        return $formatted;
    }
}

/*
|--------------------------------------------------------------------------
| User Permissions Helper
|--------------------------------------------------------------------------
*/

if (!function_exists('user_permissions')) {
    /**
     * Return current logged-in user's permission-name strings stored in session at login.
     * @return array
     */
    function user_permissions(): array
    {
        $permissions = session('permissions');
        // dd($permissions);
        return is_array($permissions) ? $permissions : [];
    }
}

if (!function_exists('has_permission')) {
    /**
     * Check if user has the given permission name.
     * @param string $permissionName
     * @return bool
     */
    function has_permission(string $permissionName): bool
    {
        $permissionName = trim($permissionName);
        if ($permissionName === '') {
            return true;
        }

        // Normalize the requested permission to lowercase and dot syntax (e.g., "users.index")
        $normalizedRequest = strtolower(str_replace('-', '.', $permissionName));

        // Normalize the user's session permissions so they match the Blade checks
        $permissions = array_map(function($p) {
            return strtolower(str_replace('-', '.', trim($p)));
        }, user_permissions());

        return in_array($normalizedRequest, $permissions, true);
    }
}
