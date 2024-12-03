<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Allocation
 *
 * @property $id
 * @property $allocation_date
 * @property $fte
 * @property $resources_id
 * @property $projects_id
 * @property $status
 * @property $source
 * @property $created_at
 * @property $updated_at
 *
 * @property Project $project
 * @property Resource $resource
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Allocation extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['allocation_date', 'fte', 'resources_id', 'projects_id', 'status', 'source'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'projects_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }
    
}
