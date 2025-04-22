<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TermsAndCondition
 *
 * @property $id
 * @property $payment_terms
 * @property $validity
 * @property $assumptions
 * @property $change_management
 * @property $created_at
 * @property $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TermsAndCondition extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['payment_terms', 'validity', 'assumptions', 'change_management'];
}
