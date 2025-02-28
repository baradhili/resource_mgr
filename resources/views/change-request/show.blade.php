@extends('layouts.app')

@section('template_title')
    {{ $changeRequest->name ?? __('Show') . " " . __('Change Request') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Change Request</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('change-requests.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Record Type:</strong>
                                    {{ $changeRequest->record_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Record Id:</strong>
                                    {{ $changeRequest->record_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Field:</strong>
                                    {{ $changeRequest->field }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Old Value:</strong>
                                    {{ $changeRequest->old_value }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>New Value:</strong>
                                    {{ $changeRequest->new_value }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $changeRequest->status }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Notes:</strong>
                                    {{ $changeRequest->notes }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Requested By:</strong>
                                    {{ $changeRequest->requested_by }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Approved By:</strong>
                                    {{ $changeRequest->approved_by }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Approval Date:</strong>
                                    {{ $changeRequest->approval_date }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
