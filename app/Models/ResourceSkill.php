<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourceSkill
 *
 * @property $resource_id
 * @property $skill_id
 * @property $proficiency_levels
 * @property $created_at
 * @property $updated_at
 *
 * @property Resource $resource
 * @property Skill $skill
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ResourceSkill extends Model
{
    
    protected $perPage = 20;
    protected $table = 'resource_skill';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['resource_id', 'skill_id', 'proficiency_levels'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resource_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo(\App\Models\Skill::class, 'skill_id', 'id');
    }
    
}
