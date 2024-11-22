@extends('layouts.app')

@section('template_title')
    {{ $estimate->name ?? __('Show') . " " . __('Estimate') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Estimate</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('estimates.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $estimate->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Use Name As Title:</strong>
                                    {{ $estimate->use_name_as_title }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Expiration Date:</strong>
                                    {{ $estimate->expiration_date }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Currency Symbol:</strong>
                                    {{ $estimate->currency_symbol }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Currency Decimal Separator:</strong>
                                    {{ $estimate->currency_decimal_separator }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Currency Thousands Separator:</strong>
                                    {{ $estimate->currency_thousands_separator }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Allows To Select Items:</strong>
                                    {{ $estimate->allows_to_select_items }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tags:</strong>
                                    {{ $estimate->tags }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estimate Owner:</strong>
                                    {{ $estimate->estimate_owner }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Partner:</strong>
                                    {{ $estimate->partner }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Total Cost:</strong>
                                    {{ $estimate->total_cost }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Created By:</strong>
                                    {{ $estimate->created_by }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Updated By:</strong>
                                    {{ $estimate->updated_by }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
