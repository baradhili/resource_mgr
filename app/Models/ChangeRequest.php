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
        return $this->morphTo();
    }

    public function allocation()
    {
        return $this->relation('record', Allocation::class);
    }

    public function demand()
    {
        return $this->relation('record', Demand::class);
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
