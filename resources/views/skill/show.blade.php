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
                                    <strong>Category:</strong>
                                    {{ $skill->category }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Keywords:</strong>
                                    {{ implode(', ', json_decode($skill->keywords)) }}
                                </div>
                                
                                <div class="form-group mb-2 mb20">
                                    <strong>Certifications:</strong>
                                    {{ $skill->certifications }}
                                </div>
                                <!-- <div class="form-group mb-2 mb20">
                                    <strong>Occupations:</strong>
                                    {{ implode(', ', array_column(json_decode($skill->occupations), 'targetNodeName')) }}
                                </div> -->
                                <!-- <div class="form-group mb-2 mb20">
                                    <strong>License:</strong>
                                    {{ $skill->license }}
                                </div> -->
                                <div class="form-group mb-2 mb20">
                                    <strong>Derived From:</strong>
                                    <a href="{{ json_decode($skill->derived_from)->id }}" target="_blank">{{ json_decode($skill->derived_from)->author }}</a>
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Source Id:</strong>
                                    <a href="{{ $skill->source_id }}" target="_blank">{{ $skill->source_id }}</a>
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Authors:</strong>
                                    {{ implode(', ', json_decode($skill->authors)) }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
