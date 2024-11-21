<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @property $id
 * @property $name
 * @property $guard_name
 * @property $created_at
 * @property $updated_at
 *
 * @property ModelHasPermission[] $modelHasPermissions
 * @property RoleHasPermission[] $roleHasPermissions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Permission extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'guard_name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelHasPermissions()
    {
        return $this->hasMany(\App\Models\ModelHasPermission::class, 'id', 'permission_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roleHasPermissions()
    {
        return $this->hasMany(\App\Models\RoleHasPermission::class, 'id', 'permission_id');
    }
    
}
