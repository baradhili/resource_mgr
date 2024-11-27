@extends('layouts.app')

@section('template_title')
    {{ $demand->name ?? __('Show') . " " . __('Demand') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Demand</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('demands.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Demand Start Date:</strong>
                                    {{ $demand->start_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Demand End Date:</strong>
                                    {{ $demand->end_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong> Fte:</strong>
                                    {{ $demand->fte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Total Fte:</strong>
                                    {{ $demand->total_fte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $demand->status }}
                                </div>
                                
                                <div class="form-group mb-2 mb20">
                                    <strong>Resource Type:</strong>
                                    {{ $demand->resource_type }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
