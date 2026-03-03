<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Project;
use App\Models\User;
use App\Models\ResourceManagement\Resource;
use App\Models\ResourceManagement\Skill;
use App\Models\ResourceManagement\Estimate;
use App\Models\ResourceManagement\ForecastDemand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;
use RuntimeException;

class DemandRequest extends Model
{
    use HasUuids;

    protected $table = 'demand_requests';

    protected $fillable = [
        'client_id',
        'project_id',
        'requester_id',
        'forecast_demand_id',
        'status',
        'priority',
        'role_title',
        'quantity_fte',
        'start_date',
        'end_date',
        'allocated_resource_id',
        'filled_at',
        // Funding Fields
        'is_funded',
        'funding_source',
        'budget_code',
        'approved_budget_amount',
        'funded_estimate_id',
        'funded_by_id',
        'funded_at',
    ];

    protected $casts = [
        'status' => DemandStatus::class,
        'funding_source' => FundingSource::class,
        'priority' => 'string',
        'quantity_fte' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'filled_at' => 'datetime',
        'is_funded' => 'boolean',
        'approved_budget_amount' => 'decimal:2',
        'funded_at' => 'datetime',
    ];

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function allocatedResource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'allocated_resource_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'demand_request_skills')
            ->withPivot('competency_level')
            ->withTimestamps();
    }

    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class);
    }

    public function forecastDemand(): BelongsTo
    {
        return $this->belongsTo(ForecastDemand::class, 'forecast_demand_id');
    }

    public function fundedEstimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class, 'funded_estimate_id');
    }

    public function fundedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'funded_by_id');
    }

    // ------------------------------------------------------------------
    // Scopes
    // ------------------------------------------------------------------

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [
            DemandStatus::PendingEstimate,
            DemandStatus::Estimated,
            DemandStatus::Approved,
            DemandStatus::Sourcing,
        ]);
    }

    public function scopeUnallocated($query)
    {
        return $query->whereNull('allocated_resource_id')
            ->where('status', '!=', DemandStatus::Rejected);
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'critical')
            ->where('start_date', '<=', Carbon::now()->addWeek());
    }

    public function scopeNeedsEstimate($query)
    {
        return $query->where('status', DemandStatus::PendingEstimate);
    }

    public function scopeFunded($query)
    {
        return $query->where('is_funded', true);
    }

    public function scopeUnfunded($query)
    {
        return $query->where('is_funded', false);
    }

    public function scopeReadyToAllocate($query)
    {
        // Governance: Only allocate if Approved AND Funded
        return $query->where('status', DemandStatus::Approved)
            ->where('is_funded', true);
    }

    // ------------------------------------------------------------------
    // Business Logic
    // ------------------------------------------------------------------

    public function createEstimate(array $data): Estimate
    {
        $estimate = $this->estimates()->create($data);

        $this->update([
            'status' => DemandStatus::Estimated,
        ]);

        return $estimate;
    }

    public function getLatestEstimateAttribute(): ?Estimate
    {
        return $this->estimates()->latest()->first();
    }

    /**
     * Approve the estimate and move to sourcing.
     * Does not fund the request yet.
     */
    public function approveEstimate(): void
    {
        $this->update([
            'status' => DemandStatus::Approved,
        ]);
    }

    /**
     * Mark the request as funded.
     * Requires an approved estimate to link against.
     */
    public function markAsFunded(
        FundingSource $source,
        string $budgetCode,
        float $amount,
        User $approver,
        ?int $estimateId = null
    ): void {
        if ($this->status !== DemandStatus::Approved) {
            throw new RuntimeException("Cannot fund a demand request that is not approved.");
        }

        $this->update([
            'is_funded' => true,
            'funding_source' => $source,
            'budget_code' => $budgetCode,
            'approved_budget_amount' => $amount,
            'funded_by_id' => $approver->id,
            'funded_at' => Carbon::now(),
            'funded_estimate_id' => $estimateId ?? $this->latest_estimate?->id,
            // Automatically move to sourcing once funded
            'status' => DemandStatus::Sourcing,
        ]);
    }

    /**
     * Revoke funding (e.g., budget cut).
     */
    public function markAsUnfunded(): void
    {
        $this->update([
            'is_funded' => false,
            'funding_source' => null,
            'budget_code' => null,
            'approved_budget_amount' => null,
            'funded_by_id' => null,
            'funded_at' => null,
            'funded_estimate_id' => null,
            // Revert status to Approved (waiting for funding)
            'status' => DemandStatus::Approved,
        ]);
    }

    /**
     * Assign a Resource to fulfill this demand.
     * Governance Check: Prevents allocation if not funded.
     */
    public function allocate(Resource $resource): void
    {
        // Governance Rule: Do not allocate scarce resources to unfunded work
        if (!$this->is_funded) {
            throw new RuntimeException("Cannot allocate resource to unfunded demand request.");
        }

        $this->update([
            'allocated_resource_id' => $resource->id,
            'status' => DemandStatus::Fulfilled,
            'filled_at' => Carbon::now(),
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => DemandStatus::Rejected,
        ]);
    }

    public function isOverdue(): bool
    {
        return in_array($this->status, [DemandStatus::Sourcing, DemandStatus::Approved], true)
            && $this->start_date?->isPast() === true;
    }
}