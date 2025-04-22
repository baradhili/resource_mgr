<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourceSkill
 *
 * @property $resources_id
 * @property $skills_id
 * @property $proficiency_levels
 * @property $created_at
 * @property $updated_at
 * @property resource $resource
 * @property Skill $skill
 *
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
    protected $fillable = ['resources_id', 'skills_id', 'proficiency_levels'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo(\App\Models\Skill::class, 'skills_id', 'id');
    }
}
