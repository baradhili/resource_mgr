<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 *
 * @property $id
 * @property $empowerID
 * @property $name
 * @property $projectManager
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Allocation[] $allocations
 * @property Demand[] $demands
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
    protected $fillable = ['empowerID', 'name', 'projectManager', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations()
    {
        return $this->hasMany(\App\Models\Allocation::class, 'id', 'projects_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demands()
    {
        return $this->hasMany(\App\Models\Demand::class, 'id', 'projects_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(\App\Models\ServiceCatalogue::class, 'project_service')
            ->withPivot('quantity', 'total_cost')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectRegions()
    {
        return $this->hasMany(\App\Models\ProjectRegion::class, 'id', 'project_id');
    }

}
