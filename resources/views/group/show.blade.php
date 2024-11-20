@extends('layouts.app')

@section('template_title')
    {{ $group->name ?? __('Show') . " " . __('Group') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Group</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('groups.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <!-- <div class="form-group mb-2 mb20">
                                    <strong>Team Id:</strong>
                                    {{ $group->team_id }}
                                </div> -->
                                <div class="form-group mb-2 mb20">
                                    <strong>Code:</strong>
                                    {{ $group->code }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $group->name }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
