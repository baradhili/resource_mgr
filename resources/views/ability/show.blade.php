@extends('layouts.app')

@section('template_title')
    {{ $ability->name ?? __('Show') . " " . __('Ability') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Ability</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('abilities.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Team Id:</strong>
                                    {{ $ability->team_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $ability->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Title:</strong>
                                    {{ $ability->title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Entity Type:</strong>
                                    {{ $ability->entity_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Entity Id:</strong>
                                    {{ $ability->entity_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
