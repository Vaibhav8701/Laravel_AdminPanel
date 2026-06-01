<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'module_permissions';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'module_id',
        'permission_name',
    ];

    protected $casts = [
        'module_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_permissions',
            'permission_id',
            'user_id'
        );
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get All With Modules (Equivalent of CI4 getAllWithModules)
    |--------------------------------------------------------------------------
    */

    public static function getAllWithModules()
    {
        return self::with('module')
            ->orderBy('module_id', 'ASC')
            ->orderBy('permission_name', 'ASC')
            ->get()
            ->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'permission_name' => $permission->permission_name,
                    'module_id' => $permission->module_id,
                    'module_name' => $permission->module->name ?? null,
                    'parent_id' => $permission->module->parent_id ?? 0,
                    'created_at' => $permission->created_at,
                    'updated_at' => $permission->updated_at,
                ];
            });
    }

}
