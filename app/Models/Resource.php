<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Resource
 *
 * @property $id
 * @property $full_name
 * @property $empowerID
 * @property $adID
 * @property $resource_type
 * @property $baseAvailability
 * @property $region_id
 * @property $location_id
 *
 * @property Allocation[] $allocations
 * @property Contract[] $contracts
 * @property Leave[] $leaves
 * @property ResourceSkill[] $skills
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Resource extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['full_name', 'empowerID', 'adID'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations()
    {
        return $this->hasMany(\App\Models\Allocation::class, 'resources_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts()
    {
        return $this->hasMany(\App\Models\Contract::class, 'resources_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves()
    {
        return $this->hasMany(\App\Models\Leave::class, 'resources_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skills()
    {
        return $this->hasMany(\App\Models\ResourceSkill::class, 'resources_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id', 'id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id', 'id')->withDefault();
    }
    
}

