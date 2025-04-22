<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property Allocation[] $allocations
 * @property Contract[] $contracts
 * @property Leave[] $leaves
 * @property ResourceSkill[] $skills
 * @property Region $region
 * @property Location $location
 * @property User $user
 * @property ResourceType $resourceType
 *
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
    public function allocations(): HasMany
    {
        return $this->hasMany(\App\Models\Allocation::class, 'resources_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(\App\Models\Contract::class, 'resources_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(\App\Models\Leave::class, 'resources_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Skill::class, 'resource_skill', 'resources_id', 'skills_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id', 'id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id', 'id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(\App\Models\User::class, 'resource_id', 'id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type', 'id')->withDefault();
    }

    /**
     * Get all resources that match a list of resource_type.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getResourcesByTypes(array $resourceTypes)
    {
        return self::whereIn('resource_type', $resourceTypes)->get();
    }

    /**
     * Get the 'permanent' value for the resource's contracts.
     *
     * @return bool|null
     */
    public function employmentStatus()
    {
        return $this->contracts()->value('permanent');
    }
}
