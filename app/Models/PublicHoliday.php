<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PublicHoliday
 *
 * @property $id
 * @property $date
 * @property $name
 * @property $region_id
 * @property $created_at
 * @property $updated_at
 * @property Region $region
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PublicHoliday extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['date', 'name', 'region_id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id', 'id');
    }
}
