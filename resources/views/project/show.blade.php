@extends('layouts.app')

@section('template_title')
    {{ $project->name ?? __('Show') . ' ' . __('Project') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} {{ __('Project') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('projects.index') }}">{{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('Start Date') }}:</strong>
                            {{ $project->start_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('End Date') }}:</strong>
                            {{ $project->end_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('Empowerid') }}:</strong>
                            {{ $project->empowerID }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('Name') }}:</strong>
                            {{ $project->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('Project Manager') }}:</strong>
                            {{ $project->projectManager }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('Status') }}:</strong>
                            {{ $project->status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('Assigned Resources') }}:</strong><small>* {{ __('means currently allocated to this project') }}</small>
                            <ul>
                                @foreach ($resources as $resource)
                                    <li>{{ $resource->current ? '*' : '' }} {{ $resource->full_name }}
                                        ({{ $resource->resourceType_name }})</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>{{ __('Open Demands') }}:</strong>
                            <ul>
                                @foreach ($demands as $key => $demand)
                                    <li>{{ $demand['resource_type'] }} ({{ number_format($demand['fte'] * 100, 2) }}% {{ __('average FTE') }})
                                        <small>{{ $demand['start'] }} - {{ $demand['end'] }}</small></li>
                                @endforeach
                            </ul>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
