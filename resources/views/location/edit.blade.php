@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Location
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-6">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('locations.update', $location->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('location.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
