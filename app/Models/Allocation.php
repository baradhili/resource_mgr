<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Allocation
 *
 * @property int $id
 * @property string $source
 * @property string|null $allocation_date
 * @property string|null $fte
 * @property int $resources_id
 * @property int $projects_id
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property Project $project
 * @property resource $resource
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Allocation extends Model
{
    use HasFactory;
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['allocation_date', 'fte', 'resources_id', 'projects_id', 'status', 'source'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Project::class, 'projects_id', 'id');
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }
}
