<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectRegion
 *
 * @property $id
 * @property $project_id
 * @property $created_at
 * @property $updated_at
 * @property $region_id
 * @property Project $project
 * @property Region $region
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProjectRegion extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['project_id', 'region_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id', 'id');
    }
}
