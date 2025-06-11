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
                            <span class="card-title">{{ __('Show') }} Project</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('projects.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>Start Date:</strong>
                            {{ $project->start_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>End Date:</strong>
                            {{ $project->end_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Empowerid:</strong>
                            {{ $project->empowerID }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $project->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Projectmanager:</strong>
                            {{ $project->projectManager }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status:</strong>
                            {{ $project->status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Assigned resources:</strong><small>* means currently allocated to this project</small>
                            <ul>
                                @foreach ($resources as $resource)
                                    <li>{{ $resource->current ? '*' : '' }} {{ $resource->full_name }}
                                        ({{ $resource->resourceType_name }})</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Open Demands:</strong>
                            <ul>
                                @foreach ($demands as $key => $demand)
                                    <li>{{ $demand['resource_type'] }} ({{ number_format($demand['fte'] * 100, 2) }}% average FTE)
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
