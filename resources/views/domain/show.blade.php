@extends('layouts.app')

@section('template_title')
    {{ $domain->name ?? __('Show') . " " . __('Domain') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Domain</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('domains.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $domain->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Business Partner Name:</strong>
                                    {{ $domain->business_partner_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $domain->description }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
