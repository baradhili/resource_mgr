<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Project
 *
 * @property $id
 * @property $start_date
 * @property $end_date
 * @property $empowerID
 * @property $name
 * @property $projectManager
 * @property $created_at
 * @property $updated_at
 * @property $status
 * @property Client $client
 * @property Allocation[] $allocations
 * @property Demand[] $demands
 * @property ProjectRegion[] $projectRegions
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['client_id', 'start_date', 'end_date', 'empowerID', 'name', 'projectManager', 'status'];

    /**
     * Get the allocations for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(\App\Models\Allocation::class, 'projects_id', 'id');
    }

    /**
     * Get the client that owns the project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the demands for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demands(): HasMany
    {
        return $this->hasMany(\App\Models\Demand::class, 'projects_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function projectRegions()
    // {
    //     return $this->hasMany(\App\Models\ProjectRegion::class, foreignKey: 'projects_id','id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function projectServices()
    // {
    //     return $this->hasMany(\App\Models\ProjectService::class, foreignKey: 'projects_id','id');
    // }

}
