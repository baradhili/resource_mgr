@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Public Holiday
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Public Holiday</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('public-holidays.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('public-holiday.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
