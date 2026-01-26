<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Assumption
 *
 * @property $id
 * @property $description
 * @property $impact
 * @property $created_at
 * @property $updated_at
 * @property $estimate_id
 * @property Estimate $estimate
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Assumption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['description', 'impact', 'estimate_id'];

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Estimate::class, 'estimate_id', 'id');
    }
}
