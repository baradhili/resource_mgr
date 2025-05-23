<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Location
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 * @property $region_id
 * @property Region $region
 * @property resource[] $resources
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Location extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'region_id'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id', 'id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(\App\Models\Resource::class, 'id', 'location_id');
    }
}
