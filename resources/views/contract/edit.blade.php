@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Contract
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6">

                <div class="card card-default">

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('contracts.update', $contract->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('contract.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
