@extends('layouts.app')

@section('template_title')
    {{ $stagingDemand->name ?? __('Show') . " " . __('Staging Demand') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Staging Demand</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('staging-demands.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Demand Date:</strong>
                                    {{ $stagingDemand->demand_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fte:</strong>
                                    {{ $stagingDemand->fte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $stagingDemand->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Resource Type:</strong>
                                    {{ $stagingDemand->resource_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Projects Id:</strong>
                                    {{ $stagingDemand->projects_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Source:</strong>
                                    {{ $stagingDemand->source }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
