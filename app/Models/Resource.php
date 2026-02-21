<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

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
    use HasFactory;

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

    public function allocations(): HasMany
    {
        return $this->hasMany(\App\Models\Allocation::class, 'resources_id', 'id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(\App\Models\Contract::class, 'resources_id', 'id');
    }
    /**
     * Get the current contract for this resource.
     *
     * The current contract is the one that is active at the current date. There is only ever one current contract per resource.
     * A contract is considered active if its start date is less than or equal to the current date,
     * and its end date is greater than or equal to the current date.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentContract(): HasOne
    {
        return $this->hasOne(Contract::class, 'resources_id')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    /**
     * Get all the leaves for this resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(\App\Models\Leave::class, 'resources_id', 'id');
    }

    /**
     * Get the active leaves for this resource.
     *
     * Leaves are considered active if their end date is greater than or equal to the current date.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeLeaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'resources_id')
            ->where('end_date', '>=', Carbon::now());
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Skill::class, 'resource_skill', 'resources_id', 'skills_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id', 'id')->withDefault();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id', 'id')->withDefault();
    }

    public function user(): HasOne
    {
        return $this->hasOne(\App\Models\User::class, 'resource_id', 'id')->withDefault();
    }

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type', 'id')->withDefault();
    }

    /**
     * Get all resources that match a list of resource_type.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getResourcesByTypes(array $resourceTypes): Collection
    {
        return self::whereIn('resource_type', $resourceTypes)->get();
    }

    /**
     * Get the 'permanent' value for the resource's contracts.
     *
     * Return true for Permanent, false for contract, null if no contracts
     * 
     * @return bool|null
     */
    public function employmentStatus(): bool|null
    {
        $contracts = $this->contracts;

        if ($contracts->isEmpty()) {
            return null;
        }

        return $contracts->value('permanent') === true;
    }


}
