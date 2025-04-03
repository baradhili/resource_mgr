@extends('layouts.app')

@section('template_title')
    Resources and Availability
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Resources and Availability') }}
                            </span>
                            <div class="float-right">
                                @can('resources.create')
                                    <a href="{{ route('resources.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Create New') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @elseif ($message = Session::get('error'))
                        <div class="alert alert-danger m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Full Name</th>
                                        <!-- Dynamically add columns for the next twelve months -->
                                        @foreach ($nextTwelveMonths as $month)
                                            <th>{{ $month['monthName'] }} {{ $month['year'] }}</th>
                                        @endforeach
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @if ($paginatedResourceAvailability->isNotEmpty())
                                        @foreach ($paginatedResourceAvailability as $key => $resource)
                                            <tr>
                                                 <td>{{ $resource['name'] }}</td>
                                                <!-- Populate availability for each month -->
                                                @foreach ($nextTwelveMonths as $month)
                                                    @php
                                                        $monthKey =
                                                            $month['year'] .
                                                            '-' .
                                                            str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                        $availability =
                                                            $paginatedResourceAvailability[$key]['availability'][
                                                                $monthKey
                                                            ] ?? '-';
                                                    @endphp
                                                    <td>{{ $availability }}</td>
                                                @endforeach
                                                <td>
                                                    <a class="btn btn-sm btn-primary"
                                                        href="{{ route('resources.allocations', $key) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <!-- <a class="btn btn-sm btn-success"
                                                        href="{{ route('resources.edit', $key) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="{{ count($nextTwelveMonths) + 2 }}" class="text-center">
                                                No data available
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $paginatedResourceAvailability->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
