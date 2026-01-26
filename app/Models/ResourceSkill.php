<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    use HasFactory;

    protected $table = 'resource_skill';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['resources_id', 'skills_id', 'proficiency_levels'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Skill::class, 'skills_id', 'id');
    }
}
