<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Region
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @property Location[] $locations
 * @property ProjectRegion[] $projectRegions
 * @property Resource[] $resources
 * @package App
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
    protected $fillable = ['name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(\App\Models\Location::class, 'id', 'region_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectRegions()
    {
        return $this->hasMany(\App\Models\ProjectRegion::class, 'id', 'region_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany(\App\Models\Resource::class, 'id', 'region_id');
    }
    
    public function publicHolidays()
    {
        return $this->hasMany(\App\Models\PublicHoliday::class);
    }
}
