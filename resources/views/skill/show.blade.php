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
                                    <strong>Context:</strong>
                                    {{ $skill->context }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Employers:</strong>
                                    {{ $skill->employers }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Keywords:</strong>
                                    {{ $skill->keywords }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Category:</strong>
                                    {{ $skill->category }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Certifications:</strong>
                                    {{ $skill->certifications }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Occupations:</strong>
                                    {{ $skill->occupations }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>License:</strong>
                                    {{ $skill->license }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Derived From:</strong>
                                    {{ $skill->derived_from }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Source Id:</strong>
                                    {{ $skill->source_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Type:</strong>
                                    {{ $skill->type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Authors:</strong>
                                    {{ $skill->authors }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
