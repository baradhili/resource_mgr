<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Item
 *
 * @property $id
 * @property $description
 * @property $duration
 * @property $price
 * @property $obligatory
 * @property $position
 * @property $created_at
 * @property $updated_at
 * @property $estimate_id
 * @property Estimate $estimate
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Item extends Model
{
    use HasFactory;
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['description', 'duration', 'price', 'obligatory', 'position', 'estimate_id'];

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Estimate::class, 'estimate_id', 'id');
    }
}
