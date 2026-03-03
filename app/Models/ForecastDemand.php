<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User;
use App\Models\DemandRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ForecastDemand extends Model
{
    use HasUuids;

    protected $table = 'forecast_demands';

    protected $fillable = [
        'client_id',
        'opportunity_id', // External CRM ID
        'owner_id',       // Business Partner
        'stage',
        'start_year',
        'start_quarter',  // 1-4
        'duration_months',
        'estimated_budget',
        'status',
        'notes',
    ];

    protected $casts = [
        'stage' => ForecastStage::class,
        'status' => ForecastStatus::class,
        'win_probability' => 'integer', // 0-100
        'quantity_fte' => 'decimal:2',
        'start_year' => 'integer',
        'start_quarter' => 'integer', // 1-4
        'duration_months' => 'integer',
        'estimated_budget' => 'decimal:2',
    ];

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Estimates created during the Proposal/Estimate stage.
     * Estimate is the single source of truth for proposal details.
     */
    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class, 'forecast_demand_id');
    }

    // ------------------------------------------------------------------
    // Scopes (Strategic Planning Helpers)
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', ForecastStatus::Active);
    }

    public function scopeLikelyToWin($query, int $threshold = 70)
    {
        return $query->where('win_probability', '>=', $threshold)
            ->where('status', ForecastStatus::Active);
    }

    /**
     * Filter forecasts impacting a specific future quarter.
     * Useful for Quarterly Capacity Planning meetings.
     */
    public function scopeImpactingQuarter($query, int $year, int $quarter)
    {
        // Calculate target quarter start/end in months since epoch for comparison
        $targetStartMonths = ($year * 12) + (($quarter - 1) * 3);
        $targetEndMonths = $targetStartMonths + 3;

        return $query->whereRaw(
            '(start_year * 12 + (start_quarter - 1) * 3) < ? AND (start_year * 12 + (start_quarter - 1) * 3 + duration_months) > ?',
            [$targetEndMonths, $targetStartMonths]
        );
    }

    /**
     * Get the start date as a Carbon instance (approximate).
     * Assumes start of quarter (Jan 1, Apr 1, etc).
     */
    public function getApproximateStartDateAttribute(): Carbon
    {
        $month = (($this->start_quarter - 1) * 3) + 1;
        return Carbon::create($this->start_year, $month, 1);
    }

    /**
     * Get the approximate end date.
     */
    public function getApproximateEndDateAttribute(): Carbon
    {
        return $this->approximate_start_date->addMonths($this->duration_months);
    }

    /**
     * Update the funnel stage.
     * Automatically updates status if Won or Lost.
     */
    public function updateStage(ForecastStage $stage): void
    {
        $this->update([
            'stage' => $stage,
            'status' => match ($stage) {
                ForecastStage::Won => ForecastStatus::Converted,
                ForecastStage::Lost => ForecastStatus::Archived,
                default => ForecastStatus::Active,
            },
        ]);
    }

    /**
     * TODO Create a Estimate from this high-level Forecast.
     * This is typically done when the Opportunity moves to the "Estimate" stage.
     * 
     */

    /**
     * Check if this forecast overlaps with a given date range.
     * Useful for conflict detection.
     */
    public function overlapsWith(Carbon $start, Carbon $end): bool
    {
        $forecastStart = $this->approximate_start_date;
        $forecastEnd = $this->approximate_end_date;

        return $start->lessThan($forecastEnd) && $end->greaterThan($forecastStart);
    }


}
