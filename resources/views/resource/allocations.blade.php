@extends('layouts.app')

@section('template_title')
    {{ $resource->name ?? __('Show') . ' ' . __('Resource') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Resource</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('resources.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>
                    <h2>Resource Allocations</h2>
                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>Full Name:</strong>
                            {{ $resource->full_name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Empowerid:</strong>
                            {{ $resource->empowerID }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Full Name</th>
                                        <!-- Dynamically add columns for the next twelve months -->
                                        @foreach ($nextTwelveMonths as $month)
                                            <th>{{ $month['monthName'] }} {{ $month['year'] }}</th>
                                        @endforeach

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ $project->name }}</td>
                                            <!-- Populate availability for each month -->
                                            @foreach ($nextTwelveMonths as $month)
                                                @php
                                                    $monthKey =
                                                        $month['year'] .
                                                        '-' .
                                                        str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                    $demandFTE =
                                                        $allocationArray[$project['id']]['allocation'][$monthKey] ?? '-';
                                                @endphp
                                                <td>{{ $demandFTE }}</td>
                                            @endforeach

                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td> <strong>Total</strong> </td>
                                        @foreach ($nextTwelveMonths as $month)
                                            <td>
                                                @php
                                                    $sum = 0;
                                                    foreach ($projects as $project) {
                                                        $monthKey =
                                                            $month['year'] .
                                                            '-' .
                                                            str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                        $sum += $allocationArray[$project['id']]['allocation'][$monthKey] ?? 0;
                                                    }
                                                @endphp
                                                <strong>{{ $sum }}</strong>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
