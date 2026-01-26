<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['start_date', 'end_date', 'resources_id'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }
}
