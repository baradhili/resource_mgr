<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceCatalogue
 *
 * @property $id
 * @property $service_name
 * @property $description
 * @property $required_skills
 * @property $hours_cost
 * @property $created_at
 * @property $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ServiceCatalogue extends Model
{
    protected $perPage = 20;

    protected $casts = [
        'required_skills' => 'json', // Casts the required_skills column to JSON
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['service_name', 'description', 'required_skills', 'hours_cost'];
}
