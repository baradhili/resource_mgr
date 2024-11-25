<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Scope
 *
 * @property $id
 * @property $tasks_deliverables
 * @property $timeline
 * @property $exclusions
 * @property $created_at
 * @property $updated_at
 * @property $estimate_id
 *
 * @property Estimate $estimate
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Scope extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['tasks_deliverables', 'timeline', 'exclusions', 'estimate_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estimate()
    {
        return $this->belongsTo(\App\Models\Estimate::class, 'estimate_id', 'id');
    }
    
}
