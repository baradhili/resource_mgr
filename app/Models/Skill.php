<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Skill
 *
 * @property $id
 * @property $skill_name
 * @property $skill_description
 * @property $created_at
 * @property $updated_at
 * @property $context
 * @property $employers
 * @property $keywords
 * @property $category
 * @property $certifications
 * @property $occupations
 * @property $license
 * @property $derived_from
 * @property $source_id
 * @property $type
 * @property $authors
 * @property ResourceSkill[] $resourceSkills
 *
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
    protected $fillable = ['skill_name', 'skill_description', 'context', 'employers', 'keywords', 'category', 'certifications', 'occupations', 'license', 'derived_from', 'source_id', 'type', 'authors'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resourceSkills(): HasMany
    {
        return $this->hasMany(\App\Models\ResourceSkill::class, 'id', 'skills_id');
    }
}
