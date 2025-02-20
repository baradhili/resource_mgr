@extends('layouts.app')

@section('template_title')
    {{ $fundingApprovalStage->name ?? __('Show') . " " . __('Funding Approval Stage') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Funding Approval Stage</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('funding-approval-stages.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Stage Name:</strong>
                                    {{ $fundingApprovalStage->stage_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $fundingApprovalStage->description }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
