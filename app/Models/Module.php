<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * Table Name
     */
    protected $table = 'modules';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Auto Increment
     */
    public $incrementing = true;

    /**
     * Key Type
     */
    protected $keyType = 'int';

    /**
     * Mass Assignable Fields (CI4 allowedFields)
     */
    protected $fillable = [
        'moduleid',
        'parent_id',
        'name',
        'permission',
        'deletestatus',
        'is_active',
    ];

    /**
     * Timestamps (CI4 useTimestamps = true)
     */
    public $timestamps = true;

    /**
     * Casts (optional but recommended)
     */
    protected $casts = [
        'moduleid' => 'integer',
        'parent_id' => 'integer',
        'deletestatus' => 'integer',
        'is_active' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (Optional but Recommended)
    |--------------------------------------------------------------------------
    */

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_id');
    }


    // Parent Module
    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }

    // Child Modules
    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id');
    }
}
