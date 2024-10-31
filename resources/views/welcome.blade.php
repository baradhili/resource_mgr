@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <ul>
                        <li><a href="{{ route('resources.index') }}">Resources</a></li>
                        <li><a href="{{ route('contracts.index') }}">Contracts</a></li>
                        <li><a href="{{ route('leaves.index')}}">Leave</a></li>
                        
                        <li><a href="{{ route('projects.index')}}">Projects</a></li>
                        <li><a href="{{ route('allocations.index')}}">Allocations</a></li>
                        <li><a href="{{ route('demands.index')}}">Demands</a></li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
