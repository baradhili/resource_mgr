@extends('layouts.app')

@section('template_title')
    {{ $demandRequest->name ?? __('Show') . " " . __('Demand Request') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Demand Request</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('demand-requests.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Project Id:</strong>
                                    {{ $demandRequest->project_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Requester Id:</strong>
                                    {{ $demandRequest->requester_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Forecast Demand Id:</strong>
                                    {{ $demandRequest->forecast_demand_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Allocated Resource Id:</strong>
                                    {{ $demandRequest->allocated_resource_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Funded Estimate Id:</strong>
                                    {{ $demandRequest->funded_estimate_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Funded By Id:</strong>
                                    {{ $demandRequest->funded_by_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Client Id:</strong>
                                    {{ $demandRequest->client_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $demandRequest->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Priority:</strong>
                                    {{ $demandRequest->priority }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Role Title:</strong>
                                    {{ $demandRequest->role_title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Quantity Fte:</strong>
                                    {{ $demandRequest->quantity_fte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Date:</strong>
                                    {{ $demandRequest->start_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>End Date:</strong>
                                    {{ $demandRequest->end_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Filled At:</strong>
                                    {{ $demandRequest->filled_at }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Funded:</strong>
                                    {{ $demandRequest->is_funded }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Funding Source:</strong>
                                    {{ $demandRequest->funding_source }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Budget Code:</strong>
                                    {{ $demandRequest->budget_code }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Approved Budget Amount:</strong>
                                    {{ $demandRequest->approved_budget_amount }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
