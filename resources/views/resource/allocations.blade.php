@extends('layouts.app')

@section('template_title')
    {{ $resource->name ?? __('Resource Allocation') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    
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
                                        <th>Project Name</th>
                                        <!-- Dynamically add columns for the next twelve months -->
                                        @foreach ($nextTwelveMonths as $month)
                                            <th>{{ $month['monthName'] }} {{ $month['year'] }}</th>
                                        @endforeach
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ $project->empowerID ?? '' }} - {{ $project->name }}</td>
                                            <!-- Populate availability for each month -->
                                            @foreach ($nextTwelveMonths as $month)
                                                @php
                                                    $monthKey =
                                                        $month['year'] .
                                                        '-' .
                                                        str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                    $demandFTE =
                                                        $allocationArray[$project['id']]['allocation'][$monthKey]['fte'] ??
                                                        '-';
                                                @endphp
                                                <td>{{ $demandFTE }}</td>
                                            @endforeach
                                            <td>
                                                <form
                                                    action="{{ route('allocations.edit', $project->id) }}"
                                                    method="GET">
                                                    @csrf
                                                    <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Return') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td> <strong>Percent of availability allocated</strong> </td>
                                        @foreach ($nextTwelveMonths as $month)
                                            <td>
                                            @php
                                                $sumPercentage = 0;
                                                foreach ($projects as $project) {
                                                    $monthKey =
                                                        $month['year'] .
                                                        '-' .
                                                        str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                    $sumPercentage +=
                                                        $allocationArray[$project->id]['allocation'][$monthKey]['percentage'] ?? 0;
                                                }
                                            @endphp
                                                <strong>{{ number_format($sumPercentage, 0) }}%</strong>
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
