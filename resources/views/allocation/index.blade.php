@extends('layouts.app')

@section('template_title')
    Allocations
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Resource Allocations (%)') }}
                            </span>

                            <div class="float-right d-flex align-items-center">
                                <form action="{{ route('allocations.index') }}" method="get"
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
                                @can('allocations.create')
                                    <a href="{{ route('allocations.create') }}" class="btn btn-primary btn-sm float-right"
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
                                    @if ($paginatedResourceAllocation->isNotEmpty())
                                        @foreach ($paginatedResourceAllocation as $key => $resource)
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
                                                            $paginatedResourceAllocation[$key]['allocation'][
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
                <x-pagination :paginator="$paginatedResourceAllocation" route="allocations.index" />
            </div>
        </div>
    </div>
@endsection
