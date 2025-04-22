<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Domain
 *
 * @property $id
 * @property $name
 * @property $business_partner_name
 * @property $description
 * @property $created_at
 * @property $updated_at
 * @property Request[] $requests
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Domain extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'business_partner_name', 'description'];

    public function requests(): HasMany
    {
        return $this->hasMany(\App\Models\Request::class, 'id', 'product_group_function_domain_id');
    }
}
