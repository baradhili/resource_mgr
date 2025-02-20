<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Site
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property Request[] $requests
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Site extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(\App\Models\Request::class, 'id', 'site_id');
    }
    
}
