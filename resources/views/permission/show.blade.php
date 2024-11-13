@extends('layouts.app')

@section('template_title')
    {{ $permission->name ?? __('Show') . " " . __('Permission') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Permission</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('permissions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Team Id:</strong>
                                    {{ $permission->team_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ability Id:</strong>
                                    {{ $permission->ability_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Entity Type:</strong>
                                    {{ $permission->entity_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Entity Id:</strong>
                                    {{ $permission->entity_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Forbidden:</strong>
                                    {{ $permission->forbidden }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
