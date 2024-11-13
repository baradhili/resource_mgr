<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @property $id
 * @property $team_id
 * @property $ability_id
 * @property $entity_type
 * @property $entity_id
 * @property $forbidden
 * @property $created_at
 * @property $updated_at
 *
 * @property Ability $ability
 * @property Team $team
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Permission extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['team_id', 'ability_id', 'entity_type', 'entity_id', 'forbidden'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ability()
    {
        return $this->belongsTo(\App\Models\Ability::class, 'ability_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'team_id', 'id');
    }
    
}
