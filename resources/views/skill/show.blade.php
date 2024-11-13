@extends('layouts.app')

@section('template_title')
    {{ $skill->name ?? __('Show') . " " . __('Skill') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Skill</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('skills.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Skill Name:</strong>
                                    {{ $skill->skill_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Skill Description:</strong>
                                    {{ $skill->skill_description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sfia Code:</strong>
                                    {{ $skill->sfia_code }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sfia Level:</strong>
                                    {{ $skill->sfia_level }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
