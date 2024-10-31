@extends('layouts.app')

@section('template_title')
    {{ $contract->name ?? __('Show') . " " . __('Contract') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Contract</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('contracts.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Date:</strong>
                                    {{ $contract->start_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>End Date:</strong>
                                    {{ $contract->end_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Availability:</strong>
                                    {{ $contract->availability }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Resources Id:</strong>
                                    {{ $contract->resources_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
