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
                            <strong>Empowerid:</strong>
                            {{ $project->empowerID ?? __('Not Available') }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $project->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Projectmanager:</strong>
                            {{ $project->projectManager ?? __('Not Available') }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Resources allocated:</strong>
                            <ul>
                                @foreach ($resources as $resource)
                                    <li>
                                        {{ $resource->full_name }} - {{ $resource->resourceType->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
