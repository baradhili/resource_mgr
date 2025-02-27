<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Request
 *
 * @property $id
 * @property $demand_type_id
 * @property $product_group_function_domain_id
 * @property $site_id
 * @property $business_partner
 * @property $request_title
 * @property $background
 * @property $business_need
 * @property $problem_statement
 * @property $specific_requirements
 * @property $funding_approval_stage_id
 * @property $wbs_number
 * @property $expected_start
 * @property $expected_duration
 * @property $business_value
 * @property $business_unit
 * @property $additional_expert_contact
 * @property $attachments
 * @property $resource_type
 * @property $fte
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Service $service
 * @property FundingApprovalStage $fundingApprovalStage
 * @property Domain $domain
 * @property Site $site
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class DemandRequest extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['demand_type_id', 'product_group_function_domain_id', 'site_id', 'business_partner', 'request_title', 'background', 'business_need', 'problem_statement', 'specific_requirements', 'funding_approval_stage_id', 'wbs_number', 'expected_start', 'expected_duration', 'business_value', 'business_unit', 'additional_expert_contact', 'attachments', 'resource_type', 'fte', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'demand_type_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fundingApprovalStage()
    {
        return $this->belongsTo(\App\Models\FundingApprovalStage::class, 'funding_approval_stage_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(\App\Models\Domain::class, 'product_group_function_domain_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo(\App\Models\Site::class, 'site_id', 'id');
    }
    
}
