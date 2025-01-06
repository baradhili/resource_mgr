@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Allocation
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('allocations.update', $allocation->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @if($form_type == "one")
                                @include('allocation.formOne')
                            @else
                                @include('allocation.form')
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
