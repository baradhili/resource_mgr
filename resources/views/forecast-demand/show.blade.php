@extends('layouts.app')

@section('template_title')
    {{ $forecastDemand->name ?? __('Show') . " " . __('Forecast Demand') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Forecast Demand</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('forecast-demands.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Opportunity Id:</strong>
                                    {{ $forecastDemand->opportunity_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Owner Id:</strong>
                                    {{ $forecastDemand->owner_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Stage:</strong>
                                    {{ $forecastDemand->stage }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $forecastDemand->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Win Probability:</strong>
                                    {{ $forecastDemand->win_probability }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Client Id:</strong>
                                    {{ $forecastDemand->client_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Year:</strong>
                                    {{ $forecastDemand->start_year }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Quarter:</strong>
                                    {{ $forecastDemand->start_quarter }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Duration Months:</strong>
                                    {{ $forecastDemand->duration_months }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estimated Budget:</strong>
                                    {{ $forecastDemand->estimated_budget }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Notes:</strong>
                                    {{ $forecastDemand->notes }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
