<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Risk
 *
 * @property $id
 * @property $potential_risks
 * @property $mitigation_steps
 * @property $created_at
 * @property $updated_at
 * @property $estimate_id
 * @property Estimate $estimate
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Risk extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['potential_risks', 'mitigation_steps', 'estimate_id'];

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Estimate::class, 'estimate_id', 'id');
    }
}
