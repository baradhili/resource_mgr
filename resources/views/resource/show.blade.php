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
                                    <strong>Linked User:</strong>
                                    @if(isset($resource->user) && $resource->user && $resource->user->id)
                                        <a href="{{ route('users.show', ['user' => $resource->user->id]) }}">{{ $resource->user->name }}</a>
                                    @else
                                        No Linked User
                                    @endif
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Resource Type:</strong>
                                    {{ $resource->resourceType->name ? $resource->resourceType->name : 'N/A' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Location:</strong>
                                    {{ $resource->location ? $resource->location->name : 'N/A' }}
                                </div>

                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Skills and Proficiencies') }}</span>
                        </div>
                        <!-- <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('resource-skills.create', ['id' => $resource->id]) }}"> {{ __('Add Skill') }}</a>
                        </div> -->
                    </div>

                    <div class="card-body bg-white">
                        @if (count($skills) > 0)
                            <ul class="list-group">
                                @foreach ($skills as $skill)
                                    <li class="list-group-item">
                                        <strong>{{ $skill['skill_name'] }}</strong>: {{ $skill['proficiency_level'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ __('No skills allocated.') }}</p>
                        @endif
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Recent Projects') }}</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        @if (count($projects) > 0)
                            <ul class="list-group">
                                <li class="list-group-item">
                                    {{ htmlspecialchars($projects->pluck('name')->implode(', '), ENT_QUOTES, 'UTF-8') }}
                                </li>
                            </ul>
                        @else
                            <p>{{ __('No projects allocated.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
