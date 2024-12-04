@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Location
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('locations.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('location.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
