<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Region
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 * @property $jurisdiction
 * @property Location[] $locations
 * @property ProjectRegion[] $projectRegions
 * @property PublicHoliday[] $publicHolidays
 * @property resource[] $resources
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Region extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'jurisdiction'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(\App\Models\Location::class, 'id', 'region_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectRegions(): HasMany
    {
        return $this->hasMany(\App\Models\ProjectRegion::class, 'id', 'region_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publicHolidays(): HasMany
    {
        return $this->hasMany(\App\Models\PublicHoliday::class, 'id', 'region_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(\App\Models\Resource::class, 'id', 'region_id');
    }
}
