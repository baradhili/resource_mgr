<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Leave
 *
 * @property $id
 * @property $start_date
 * @property $end_date
 * @property $resources_id
 * @property $created_at
 * @property $updated_at
 * @property resource $resource
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Leave extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['start_date', 'end_date', 'resources_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }
}
