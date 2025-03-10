<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 *
 * @property $id
 * @property $start_date
 * @property $end_date
 * @property $empowerID
 * @property $name
 * @property $projectManager
 * @property $created_at
 * @property $updated_at
 * @property $status
 *
 * @property Allocation[] $allocations
 * @property Demand[] $demands
 * @property ProjectRegion[] $projectRegions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Project extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['start_date', 'end_date', 'empowerID', 'name', 'projectManager', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations()
    {
        return $this->hasMany(\App\Models\Allocation::class, 'projects_id','id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demands()
    {
        return $this->hasMany(\App\Models\Demand::class,  'projects_id','id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function projectRegions()
    // {
    //     return $this->hasMany(\App\Models\ProjectRegion::class, foreignKey: 'projects_id','id');
    // }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function projectServices()
    // {
    //     return $this->hasMany(\App\Models\ProjectService::class, foreignKey: 'projects_id','id');
    // }
    
}
