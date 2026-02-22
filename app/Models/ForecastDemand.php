<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User;
use App\Models\ResourceManagement\DemandRequest;
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
        'opportunity_id', // External CRM ID
        'owner_id',       // Sales Rep
        'stage',
        'win_probability',
        'role_title',     // High level role (e.g., "Development Team")
        'quantity_fte',
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

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The detailed DemandRequests generated from this forecast.
     * This is where the specific Roles, Skills, and Estimates live.
     */
    public function demandRequests(): HasMany
    {
        return $this->hasMany(DemandRequest::class, 'forecast_demand_id');
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
        return $query->where(function ($q) use ($year, $quarter) {
            // Starts in this quarter
            $q->where('start_year', $year)
              ->where('start_quarter', $quarter)
              // OR started earlier but overlaps into this quarter
              ->orWhere(function ($sub) use ($year, $quarter) {
                  $sub->where('start_year', '<', $year)
                      ->orWhere(function ($s) use ($year, $quarter) {
                          $s->where('start_year', $year)
                            ->where('start_quarter', '<', $quarter);
                      });
              });
        });
    }

    public function scopeUnconverted($query)
    {
        return $query->where('status', ForecastStatus::Active)
                     ->whereDoesntHave('demandRequests');
    }

    // ------------------------------------------------------------------
    // Business Logic (The "Resource Manager" Brain)
    // ------------------------------------------------------------------

    /**
     * Calculate weighted FTE for capacity planning.
     * 1.0 FTE at 50% probability = 0.5 weighted FTE.
     */
    public function getWeightedFteAttribute(): float
    {
        return (float) $this->quantity_fte * ($this->win_probability / 100);
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
            'status' => match($stage) {
                ForecastStage::Won => ForecastStatus::Converted,
                ForecastStage::Lost => ForecastStatus::Archived,
                default => ForecastStatus::Active,
            },
        ]);
    }

    /**
     * Create a detailed DemandRequest from this high-level Forecast.
     * This is typically done when the Opportunity moves to the "Estimate" stage.
     * The DemandRequest will then hold the specific Skills/Roles/Estimates.
     */
    public function createDetailedRequest(array $details = []): DemandRequest
    {
        return DB::transaction(function () use ($details) {
            $request = $this->demandRequests()->create([
                'project_id' => $details['project_id'] ?? null,
                'requester_id' => $this->owner_id,
                'forecast_demand_id' => $this->id,
                'role_title' => $details['role_title'] ?? $this->role_title,
                'quantity_fte' => $details['quantity_fte'] ?? $this->quantity_fte,
                'start_date' => $details['start_date'] ?? $this->approximate_start_date,
                'end_date' => $details['end_date'] ?? $this->approximate_end_date,
                'priority' => 'normal',
                'status' => \App\Enums\DemandStatus::PendingEstimate,
            ]);

            // If deal is already won, move request to Approved/Sourcing
            if ($this->stage === ForecastStage::Won) {
                $request->approveEstimate(); // Skip estimate step if already priced
            }

            return $request;
        });
    }

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