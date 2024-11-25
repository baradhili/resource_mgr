@extends('layouts.app')

@section('template_title')
    {{ $termsAndCondition->name ?? __('Show') . " " . __('Terms And Condition') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Terms And Condition</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('terms-and-conditions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Payment Terms:</strong>
                                    {{ $termsAndCondition->payment_terms }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Validity:</strong>
                                    {{ $termsAndCondition->validity }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Assumptions:</strong>
                                    {{ $termsAndCondition->assumptions }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Change Management:</strong>
                                    {{ $termsAndCondition->change_management }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
