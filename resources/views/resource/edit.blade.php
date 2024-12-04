@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Resource
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-6">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('resources.update', $resource->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('resource.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
