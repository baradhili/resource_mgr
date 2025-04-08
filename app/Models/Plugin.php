<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Plugin
 *
 * @property $id
 * @property $name
 * @property $type
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Plugin extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'type', 'description'];


}
