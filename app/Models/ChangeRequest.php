<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_type',
        'record_id',
        'field',
        'old_value',
        'new_value',
        'status',
        'notes',
        'requested_by',
        'approved_by',
        'approval_date',
    ];

    public function record(): MorphTo
    {
        return $this->morphTo(null, 'record_type');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors to handle JSON fields
    public function getOldValueAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getNewValueAttribute($value)
    {
        return json_decode($value, true);
    }

    // Mutators to handle JSON fields
    public function setOldValueAttribute($value)
    {
        $this->attributes['old_value'] = json_encode($value);
    }

    public function setNewValueAttribute($value)
    {
        $this->attributes['new_value'] = json_encode($value);
    }
}
