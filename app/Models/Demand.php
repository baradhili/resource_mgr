<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Demand
 *
 * @property int $id
 * @property int $client_id
 * @property int|null $project_id
 * @property string|null $expected_start_date
 * @property string|null $expected_end_date
 * @property string|null $fte
 * @property string $status
 * @property string|null $source
 * @property string|null $notes
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $demand_date
 * @property string|null $resource_type
 * @property Client|null $client
 * @property Project|null $project
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Demand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['client_id', 'project_id', 'expected_start_date', 'expected_end_date', 'fte', 'status', 'source', 'notes', 'demand_date', 'resource_type'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id', 'id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_id', 'id');
    }

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ResourceType::class, 'resource_type', 'id');
    }

}
