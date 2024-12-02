<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Skill
 *
 * @property $id
 * @property $skill_name
 * @property $skill_description
 * @property $sfia_code
 * @property $sfia_level
 * @property $created_at
 * @property $updated_at
 *
 * @property ResourceSkill[] $resourceSkills
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Skill extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['skill_name', 'skill_description', 'sfia_code', 'sfia_level'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany(\App\Models\Resource::class, 'resource_skill', 'skill_id', 'resources_id');
    }
    
}
