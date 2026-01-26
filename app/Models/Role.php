<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Role
 *
 * @property $id
 * @property $name
 * @property $guard_name
 * @property $created_at
 * @property $updated_at
 * @property ModelHasRole[] $modelHasRoles
 * @property RoleHasPermission[] $roleHasPermissions
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'guard_name'];

    public function modelHasRoles(): HasMany
    {
        return $this->hasMany(\App\Models\ModelHasRole::class, 'id', 'role_id');
    }

    public function roleHasPermissions(): HasMany
    {
        return $this->hasMany(\App\Models\RoleHasPermission::class, 'id', 'role_id');
    }
}
