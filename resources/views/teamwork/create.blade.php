@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Create a new team') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('teams.index') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="card-body">
                        <form class="form-horizontal" method="post" action="{{ route('teams.store') }}">
                            @csrf

                            @include('teamwork.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
