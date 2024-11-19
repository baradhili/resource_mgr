@extends('layouts.app')

@section('template_title')
    {{ $service->name ?? __('Show') . " " . __('Service') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Service</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('services.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Service Name:</strong>
                                    {{ $service->service_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $service->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Required Skills:</strong>
                                    {{ $service->required_skills }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Hours Cost:</strong>
                                    {{ $service->hours_cost }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
