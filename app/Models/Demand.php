<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Demand
 *
 * @property $id
 * @property $demand_date
 * @property $fte
 * @property $status
 * @property $resource_type
 * @property $projects_id
 * @property $source
 * @property $created_at
 * @property $updated_at
 * @property Project $project
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Demand extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['demand_date', 'fte', 'status', 'resource_type', 'projects_id', 'source'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'projects_id', 'id');
    }
}
