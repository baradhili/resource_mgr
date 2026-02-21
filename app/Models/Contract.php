<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['start_date', 'end_date', 'availability', 'resources_id', 'permanent'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $appends = ['tenure_years'];

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
    public function getTenureYearsAttribute(): float
    {
        if (!$this->start_date || !$this->end_date) {
            return 0.0;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        // Calculate the difference in years with one decimal place
        return round($start->diffInDays($end) / 365.25, 1);
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
            $this->end_date->floatDiffInYears($this->start_date),
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

}

