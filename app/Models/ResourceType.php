<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourceType
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ResourceType extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];
}
