@extends('layouts.app')

@section('template_title')
    {{ $team->name ?? __('Show') . " " . __('Team') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Team</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('teams.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Owner:</strong>
                                    {{ $team->owner->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $team->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Members:</strong>
                                    {{ $team->members->pluck('name')->implode(', ') }}
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
