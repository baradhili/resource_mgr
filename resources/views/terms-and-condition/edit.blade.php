@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Terms And Condition
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-6">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('terms-and-conditions.update', $termsAndCondition->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('terms-and-condition.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
