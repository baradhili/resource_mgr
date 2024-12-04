@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Demand
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 ">

                <div class="card card-default">
            
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('demands.update', $demand->projects_id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            @csrf
                            <input type="hidden" name="demand_id" value="{{ $demand->demand_id }}">
                            
                            @include('demand.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
