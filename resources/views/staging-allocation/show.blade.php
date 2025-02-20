@extends('layouts.app')

@section('template_title')
    {{ $stagingAllocation->name ?? __('Show') . " " . __('Staging Allocation') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Staging Allocation</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('staging-allocations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Allocation Date:</strong>
                                    {{ $stagingAllocation->allocation_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fte:</strong>
                                    {{ $stagingAllocation->fte }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Resources Id:</strong>
                                    {{ $stagingAllocation->resources_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Projects Id:</strong>
                                    {{ $stagingAllocation->projects_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $stagingAllocation->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Source:</strong>
                                    {{ $stagingAllocation->source }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
