<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Capability
 *
 * @property $id
 * @property $name
 * @property $code
 *
 * @property GroupCapability[] $groupCapabilities
 * @property RoleCapability[] $roleCapabilities
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Capability extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'code'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupCapabilities()
    {
        return $this->hasMany(\App\Models\GroupCapability::class, 'id', 'capability_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roleCapabilities()
    {
        return $this->hasMany(\App\Models\RoleCapability::class, 'id', 'capability_id');
    }
    
}
