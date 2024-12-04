@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Allocation
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('allocations.populate') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('allocation.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
