@extends('layouts.app')

@section('template_title')
    Capacity
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Resource Capacity (%)') }}
                            </span>

                            <div class="float-right d-flex align-items-center">
                                <form action="{{ route('capacity.index') }}" method="get"
                                    class="d-inline-flex align-items-center" id="filterForm">

                                    <select class="form-control" id="region_id" name="region_id" style="width: auto;"
                                        onchange="document.getElementById('filterForm').submit(); return false;">
                                        <option value="" selected>All</option>
                                        @foreach ($regions as $region)
                                            @if ($region->id !== null)
                                                <option value="{{ $region->id }}"
                                                    {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                                    {{ $region->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </form>
                                &nbsp;
                                <a href="{{ route('capacity.export') }}" class="btn btn-success btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Export') }}
                                    </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
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
                                    @if ($paginatedResourceCapacity->isNotEmpty())
                                        @foreach ($paginatedResourceCapacity as $key => $resource)
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
                                                            $paginatedResourceCapacity[$key]['capacity'][$monthKey] ??
                                                            '-';
                                                    @endphp
                                                    <td>{{ $availability }}</td>
                                                @endforeach
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
                {!! $paginatedResourceCapacity->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
