@extends('layouts.app')

@section('template_title')
    {{ $leave->name ?? __('Show') . " " . __('Leave') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Leave</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('leaves.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Start Date:</strong>
                                    {{ $leave->start_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>End Date:</strong>
                                    {{ $leave->end_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Resources Id:</strong>
                                    {{ $leave->resources_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
