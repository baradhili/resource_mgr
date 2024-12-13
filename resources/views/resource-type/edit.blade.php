@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Resource Type
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Resource Type</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('resource-types.update', $resourceType->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('resource-type.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
