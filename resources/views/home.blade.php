@extends('layouts.app')

@section('template_title')
    Dashboard
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
            widgets:
            <ul>
                <li>Average Workload</li>
                <li>Contractor Tenure Tracker</li>
                <li>Skill Set Coverage and Gaps</li>
                <li>Unrecovered Resource Cost</li>
                <li>Upcoming Demands</li>
                <li>Demand Funnel</li>
                <li>Resource Availability by Month</li>
            </ul>
        </div>
    </div>
</div>
@endsection
