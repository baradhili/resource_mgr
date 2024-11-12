@extends('layouts.app')

@section('template_title')
    {{ $resourceSkill->name ?? __('Show') . " " . __('Resource Skill') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Resource Skill</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('resource-skills.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Resources Id:</strong>
                                    {{ $resourceSkill->resources_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Skills Id:</strong>
                                    {{ $resourceSkill->skills_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Proficiency Levels:</strong>
                                    {{ $resourceSkill->proficiency_levels }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
