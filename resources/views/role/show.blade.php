@extends('layouts.app')

@section('template_title')
    {{ $role->name ?? __('Show') . " " . __('Role') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Role</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('roles.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Team Id:</strong>
                                    {{ $role->team_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $role->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Guard Name:</strong>
                                    {{ $role->guard_name }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
