<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'approval_date'
    ];

    public function record()
    {
        return $this->morphTo(null, 'record_type');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
