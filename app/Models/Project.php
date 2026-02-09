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
     * Retrieve allocations related to the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany Has-many relation to App\Models\Allocation instances keyed by `projects_id`.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(\App\Models\Allocation::class, 'projects_id', 'id');
    }

    /**
     * Get the client that owns the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo The client relationship for this project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Retrieve the demands associated with the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany A has-many relation for Demand models belonging to the project.
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