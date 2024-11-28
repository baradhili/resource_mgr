@extends('layouts.app')

@section('template_title')
    {{ $location->name ?? __('Show') . " " . __('Location') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Location</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('locations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $location->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Region Id:</strong>
                                    {{ $location->region_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
