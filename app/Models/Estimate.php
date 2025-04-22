<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Estimate
 *
 * @property $id
 * @property $name
 * @property $use_name_as_title
 * @property $expiration_date
 * @property $currency_symbol
 * @property $currency_decimal_separator
 * @property $currency_thousands_separator
 * @property $allows_to_select_items
 * @property $tags
 * @property $total_cost
 * @property-read User $created_by
 * @property-read User $estimator
 * @property-read User $partner
 * @property-read User $last_updated_by
 * @property-read Assumption[] $assumptions
 * @property-read Item[] $items
 * @property-read Risk[] $risks
 * @property-read Scope $scope
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Estimate extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'use_name_as_title', 'expiration_date', 'currency_symbol', 'currency_decimal_separator', 'currency_thousands_separator', 'allows_to_select_items', 'tags', 'estimate_owner', 'partner', 'total_cost', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estimator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estimate_owner', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function last_updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assumptions(): HasMany
    {
        return $this->hasMany(Assumption::class, 'id', 'estimate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'id', 'estimate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class, 'id', 'estimate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function scope(): HasOne
    {
        return $this->hasOne(Scope::class, 'id', 'estimate_id');
    }
}
