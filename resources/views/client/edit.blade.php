@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Client
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('clients.update', $client->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('client.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
