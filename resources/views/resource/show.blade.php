@extends('layouts.app')

@section('template_title')
    {{ $resource->name ?? __('Show') . " " . __('Resource') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Resource</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('resources.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Full Name:</strong>
                                    {{ $resource->full_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Empowerid:</strong>
                                    {{ $resource->empowerID }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Adid:</strong>
                                    {{ $resource->adID }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
