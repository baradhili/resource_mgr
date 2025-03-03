<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contract
 *
 * @property $id
 * @property $start_date
 * @property $end_date
 * @property $availability
 * @property $resources_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Resource $resource
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Contract extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['start_date', 'end_date', 'availability', 'resources_id', 'permanent'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resources_id', 'id');
    }
    
}
