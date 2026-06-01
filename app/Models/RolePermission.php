<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    */
    protected $table = 'role_permissions';

    /*
    |--------------------------------------------------------------------------
    | Primary Key
    |--------------------------------------------------------------------------
    */
    protected $primaryKey = 'id';

    /*
    |--------------------------------------------------------------------------
    | Disable Default Timestamps
    |--------------------------------------------------------------------------
    | (Enable if your table has created_at & updated_at)
    */
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'role_id' => 'integer',
        'permission_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Role relationship
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Permission relationship
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
