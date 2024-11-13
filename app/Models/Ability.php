<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ability
 *
 * @property $id
 * @property $team_id
 * @property $name
 * @property $title
 * @property $entity_type
 * @property $entity_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Team $team
 * @property Permission[] $permissions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ability extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['team_id', 'name', 'title', 'entity_type', 'entity_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'team_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(\App\Models\Permission::class, 'id', 'ability_id');
    }
    
}
