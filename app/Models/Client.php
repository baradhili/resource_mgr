<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Client
 *
 * @property $id
 * @property $name
 * @property $contact_details
 * @property $created_at
 * @property $updated_at
 * property Project[] $projects
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'contact_details'];

    /**
     * Get the client's projects.
     *
     * @return HasMany The has-many relation linking this client to its Project models.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}