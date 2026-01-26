<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Site
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 * @property Request[] $requests
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Site extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    public function requests(): HasMany
    {
        return $this->hasMany(\App\Models\Request::class, 'id', 'site_id');
    }
}
