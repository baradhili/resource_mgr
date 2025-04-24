<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class Contract
 *
 * @property $id
 * @property $start_date
 * @property $end_date
 * @property $availability
 * @property $resources_id
 * @property $created_at
 * @property $updated_at
 * @property resource $resource
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Contract extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['start_date', 'end_date', 'availability', 'resources_id', 'permanent'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }

    /**
     * Get the tenure attribute.
     *
     * This attribute calculates the tenure of the contract based on the start
     * and end dates. If the contract is permanent, the tenure is 0.
     *
     * @return float The calculated tenure of the contract.
     */
    public function getTenureAttribute(): float
    {
        return $this->permanent ? 0 : number_format(
            Carbon::parse($this->end_date)->floatDiffInYears(Carbon::parse($this->start_date)),
            1
        );
    }


    /**
     * Get the tenure status attribute.
     *
     * This attribute determines the tenure status by comparing the calculated
     * tenure of the contract with the configured tenure setting. The status
     * can be 'normal', 'warning', or 'danger', based on how the tenure
     * compares to the configuration.
     *
     * - Returns 'normal' if the configured tenure is 0 or if the calculated
     *   tenure is less than the configured tenure minus 0.5.
     * - Returns 'warning' if the calculated tenure is greater than or equal to
     *   the configured tenure minus 0.5.
     * - Returns 'danger' if the calculated tenure is greater than or equal to
     *   the configured tenure.
     *
     * @return string The tenure status: 'normal', 'warning', or 'danger'.
     */

    public function getTenureStatusAttribute(): string
    {
        $tenure = config('app.tenure');
        $calc = $this->permanent ? 0 : number_format(
            Carbon::parse($this->end_date)->floatDiffInYears(Carbon::parse($this->start_date)),
            1
        );

        if (!$tenure) {
            return 'normal';
        }

        if ($calc >= $tenure) {
            return 'danger';
        }

        if ($calc >= $tenure - 0.5) {
            return 'warning';
        }

        return 'normal';
    }

    // Append the tenure_status attribute to the JSON output
    protected $appends = ['tenure_status', 'tenure'];
}

