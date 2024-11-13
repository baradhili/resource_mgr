<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @property $id
 * @property $team_id
 * @property $name
 * @property $description
 *
 * @property Team $team
 * @property GroupCapability[] $groupCapabilities
 * @property RoleCapability[] $roleCapabilities
 * @property TeamUser[] $teamUsers
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Role extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['team_id', 'name', 'description'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'team_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupCapabilities()
    {
        return $this->hasMany(\App\Models\GroupCapability::class, 'id', 'role_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roleCapabilities()
    {
        return $this->hasMany(\App\Models\RoleCapability::class, 'id', 'role_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamUsers()
    {
        return $this->hasMany(\App\Models\TeamUser::class, 'id', 'role_id');
    }
    
}
