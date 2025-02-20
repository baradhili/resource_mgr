@extends('layouts.app')

@section('template_title')
    {{ $request->name ?? __('Show') . " " . __('Request') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Request</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('requests.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Demand Type Id:</strong>
                                    {{ $request->demand_type_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Product Group Function Domain Id:</strong>
                                    {{ $request->product_group_function_domain_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Site Id:</strong>
                                    {{ $request->site_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Business Partner:</strong>
                                    {{ $request->business_partner }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Request Title:</strong>
                                    {{ $request->request_title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Background:</strong>
                                    {{ $request->background }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Business Need:</strong>
                                    {{ $request->business_need }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Problem Statement:</strong>
                                    {{ $request->problem_statement }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Specific Requirements:</strong>
                                    {{ $request->specific_requirements }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Funding Approval Stage Id:</strong>
                                    {{ $request->funding_approval_stage_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Wbs Number:</strong>
                                    {{ $request->wbs_number }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Expected Start:</strong>
                                    {{ $request->expected_start }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Expected Duration:</strong>
                                    {{ $request->expected_duration }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Business Value:</strong>
                                    {{ $request->business_value }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Business Unit:</strong>
                                    {{ $request->business_unit }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Additional Expert Contact:</strong>
                                    {{ $request->additional_expert_contact }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Attachments:</strong>
                                    {{ $request->attachments }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Resource Type:</strong>
                                    {{ $request->resource_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fte:</strong>
                                    {{ $request->fte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $request->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
