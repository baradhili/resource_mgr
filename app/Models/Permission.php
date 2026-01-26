<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Permission
 *
 * @property $id
 * @property $name
 * @property $guard_name
 * @property $created_at
 * @property $updated_at
 * @property ModelHasPermission[] $modelHasPermissions
 * @property RoleHasPermission[] $roleHasPermissions
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'guard_name'];

    public function modelHasPermissions(): HasMany
    {
        return $this->hasMany(\App\Models\ModelHasPermission::class, 'id', 'permission_id');
    }

    public function roleHasPermissions(): HasMany
    {
        return $this->hasMany(\App\Models\RoleHasPermission::class, 'id', 'permission_id');
    }
}
