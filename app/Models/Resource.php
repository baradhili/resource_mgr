<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
 * @property-read Allocation[] $allocations
 * @property-read Contract[] $contracts
 * @property-read Leave[] $leaves
 * @property-read ResourceSkill[] $skills
 * @property-read Region $region
 * @property-read Location $location
 * @property-read User $user
 * @property-read ResourceType $resourceType
 *
 * @mixin Builder
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

    protected $casts = [
        'resource_type' => 'integer',
        'baseAvailability' => 'float',
        'region_id' => 'integer',
        'location_id' => 'integer',
    ];

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class, 'resource_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'resource_id');
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
        return $this->hasOne(Contract::class, 'resource_id')
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
        return $this->hasMany(Leave::class, 'resource_id');
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
        return $this->hasMany(Leave::class, 'resource_id')
            ->where('end_date', '>=', Carbon::now());
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'resource_skill', 'resource_id', 'skills_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class)->withDefault();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class)->withDefault();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'resource_id')->withDefault();
    }

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type')->withDefault();
    }

    /**
     * Scope a query to only include resources with active contracts.
     */
    public function scopeWithActiveContract(Builder $query): Builder
    {
        return $query->whereHas('contracts', function (Builder $q) {
            $q->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now());
        });
    }

    /**
     * Scope a query to only include resources in a specific region.
     *
     * Usage:
     *   Resource::region(5)->get();
     *   Resource::region($regionId)->with('location')->paginate(20);
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|\App\Models\Region $region The region ID or Region instance
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegion(Builder $query, int|Region $region): Builder
    {
        $regionId = $region instanceof Region ? $region->id : $region;

        return $query->where('region_id', $regionId);
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
     * Get the employment status for a resource.
     *
     * Return true for Permanent, false for contract
     * 
     * @return bool|null
     */
    public function employmentStatus(): bool|null
    {
        return $this->currentContract?->permanent === true;
    }


}
