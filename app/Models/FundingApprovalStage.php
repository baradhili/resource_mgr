<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FundingApprovalStage
 *
 * @property $id
 * @property $stage_name
 * @property $description
 * @property $created_at
 * @property $updated_at
 * @property Request[] $requests
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class FundingApprovalStage extends Model
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['stage_name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(\App\Models\Request::class, 'id', 'funding_approval_stage_id');
    }
}
