@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Terms And Condition
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('terms-and-conditions.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('terms-and-condition.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
