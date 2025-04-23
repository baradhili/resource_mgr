@extends('layouts.app')

@section('template_title')
    {{ $contract->name ?? __('Show') . ' ' . __('Contract') }}
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
                            <strong>Resource name:</strong>
                            {{ $resource->full_name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong> Perm/Contract </strong>
                            {{ $contract->permanent ? 'Permanent' : 'Contract' }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Start Date:</strong>
                            {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>End Date:</strong>
                            {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Availability:</strong>
                            {{ $contract->availability }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Tenure at end of contract:</strong>
                            {{ $resource->tenure }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Projects overlapping contract end:</strong>
                            {{ $resource->currentProjects ? collect($resource->currentProjects)->map(fn($p) => $p->empowerID . ' - ' . $p->name . ' (' . $p->projectManager . ')')->implode(', ') : '' }}
                            @if ($resource->currentProjects->count() > 0)
                                @can('contracts.clean')
                                    <a class="btn btn-sm btn-success"
                                        href="{{ route('contracts.clean', ['end_date' => $contract->end_date, 'resource_id' => $contract->resource->id]) }}"><i
                                            class="fa fa-fw fa-edit"></i> {{ __('Return Projects') }}</a>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
