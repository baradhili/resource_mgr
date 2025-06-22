<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    use HasFactory;
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'jurisdiction'];

    public function locations(): HasMany
    {
        return $this->hasMany(\App\Models\Location::class, 'id', 'region_id');
    }

    public function projectRegions(): HasMany
    {
        return $this->hasMany(\App\Models\ProjectRegion::class, 'id', 'region_id');
    }

    public function publicHolidays(): HasMany
    {
        return $this->hasMany(\App\Models\PublicHoliday::class, 'id', 'region_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(\App\Models\Resource::class, 'id', 'region_id');
    }
}
