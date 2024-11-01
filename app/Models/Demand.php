<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Demand
 *
 * @property $id
 * @property $year
 * @property $month
 * @property $fte
 * @property $status
 * @property $projects_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Project $project
 * @package App
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
    protected $fillable = ['year', 'month', 'fte', 'status', 'projects_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'projects_id', 'id');
    }
    
}
