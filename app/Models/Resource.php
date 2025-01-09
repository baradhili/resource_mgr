<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Resource
 *
 * @property int $id
 * @property string $full_name
 * @property string $empowerID
 * @property string $userID
 * @property int $resource_type
 * @property float $baseAvailability
 * @property int $region_id
 * @property int $location_id
 *
 * @property Allocation[] $allocations
 * @property Contract[] $contracts
 * @property Leave[] $leaves
 * @property ResourceSkill[] $skills
 * @property Region $region
 * @property Location $location
 * @property User $user
 * @property ResourceType $resourceType
 * @package App\Models
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
    protected $fillable = [
        'full_name',
        'empowerID',
        'userID',
        'resource_type',
        'baseAvailability',
        'region_id',
        'location_id',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function skills()
    {
        return $this->belongsToMany(\App\Models\Skill::class, 'resource_skill', 'resources_id', 'skills_id');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'userID', 'id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class, 'resource_type', 'id')->withDefault();
    }

}

