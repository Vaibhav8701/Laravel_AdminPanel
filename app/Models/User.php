<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the roles that the user has
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Get the permissions that the user has
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    /**
     * Get all permission names for the user (direct permissions + permissions through roles)
     * @return array
     */
    public function getPermissionNames(): array
    {
        $permissionNames = [];

        // Get direct permissions
        $directPermissions = $this->permissions()
            ->pluck('permission_name')
            ->toArray();

        $permissionNames = array_merge($permissionNames, $directPermissions);

        // Get permissions through roles
        $rolePermissions = $this->roles()
            ->with('permissions')
            ->get()
            ->flatMap(function ($role) {
                return $role->permissions->pluck('permission_name');
            })
            ->toArray();

        $permissionNames = array_merge($permissionNames, $rolePermissions);

        // Return unique permission names
        return array_unique($permissionNames);
    }

    // public function posts(): HasMany
    // {
    //     return $this->hasMany(Post::class);
    // }
}
