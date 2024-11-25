<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Assumption
 *
 * @property $id
 * @property $description
 * @property $impact
 * @property $created_at
 * @property $updated_at
 * @property $estimate_id
 *
 * @property Estimate $estimate
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Assumption extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['description', 'impact', 'estimate_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estimate()
    {
        return $this->belongsTo(\App\Models\Estimate::class, 'estimate_id', 'id');
    }
    
}
