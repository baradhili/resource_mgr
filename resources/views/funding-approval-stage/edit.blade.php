@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Funding Approval Stage
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Funding Approval Stage</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('funding-approval-stages.update', $fundingApprovalStage->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('funding-approval-stage.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
