@extends('layouts.app')

@section('template_title')
    {{ $allocation->name ?? __('Show') . " " . __('Allocation') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Allocation</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('allocations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Year:</strong>
                                    {{ $allocation->year }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Month:</strong>
                                    {{ $allocation->month }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fte:</strong>
                                    {{ $allocation->fte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Resources Id:</strong>
                                    {{ $allocation->resources_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Projects Id:</strong>
                                    {{ $allocation->projects_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $allocation->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
