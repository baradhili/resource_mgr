@extends('layouts.app')

@section('template_title')
    Contracts
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Contracts') }}
                            </span>

                            <div class="float-right">
                                <form action="{{ route('contracts.index') }}" method="get"
                                    class="d-inline-flex align-items-center" id="filterForm">
                                    <div class="custom-control custom-checkbox mr-2">
                                        <input type="checkbox" class="custom-control-input" id="showOld" name="old"
                                            value="1" {{ request('old') == 1 ? 'checked' : '' }}
                                            onchange="document.getElementById('filterForm').submit()">
                                        <label class="custom-control-label" for="showOld">Show Old</label>
                                    </div>
                                    &nbsp;
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search..." style="width: auto;" value="{{ request('search') }}"
                                        onkeydown="if (event.keyCode == 13) { document.getElementById('filterForm').submit(); return false; }">
                                </form>
                                &nbsp;
                                @can('contracts.create')
                                    <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm float-right"
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
                            <table class="table table-hover table-striped">
                                <thead class="thead">
                                    <tr>
                                        <th>Resource</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Availability</th>
                                        <th>Tenure</th>
                                        <th>Perm/Contract</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contracts as $contract)
                                        @php
                                            // Calculate the difference in months between the end_date and today
                                            $endDate = \Carbon\Carbon::parse($contract->end_date);
                                            $today = \Carbon\Carbon::today();
                                            $monthsDifference = $endDate->diffInMonths($today);
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $contract->resource->full_name }}
                                                @if ($monthsDifference <= 3)
                                                    @if ($monthsDifference >= 2)
                                                        <span data-toggle="tooltip" data-placement="top"
                                                            title="{{ __('Contract ends in next 3 months') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="black" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" class="feather feather-clock">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <polyline points="12 6 12 12 16 14"></polyline>
                                                            </svg>
                                                        </span>
                                                    @elseif ($monthsDifference >= 1)
                                                        <span data-toggle="tooltip" data-placement="top"
                                                            title="{{ __('Contract ends in next 2 months') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="orange" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" class="feather feather-clock">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <polyline points="12 6 12 12 16 14"></polyline>
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <span data-toggle="tooltip" data-placement="top"
                                                            title="{{ __('Contract ends this month') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="red" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" class="feather feather-clock">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <polyline points="12 6 12 12 16 14"></polyline>
                                                            </svg>
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $contract->permanent ? 'N/A' : \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}
                                            </td>
                                            <td>{{ $contract->permanent ? 'N/A' : \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}
                                            </td>
                                            <td>{{ $contract->availability }}</td>
                                            <td>
                                                <span class="text-{{ $contract->tenure_status }}">
                                                    {{ $contract->tenure }}
                                                </span>
                                            </td>
                                            <td>{{ $contract->permanent ? 'P' : 'C' }}</td>
                                            <td>
                                                <form action="{{ route('contracts.destroy', $contract->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('contracts.show', $contract->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    @can('contracts.edit')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('contracts.edit', $contract->id) }}"><i
                                                                class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('contracts.destroy')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                                class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                    @endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <form action="{{ route('contracts.index') }}" method="get">
                    <div class="d-flex justify-content-between">
                        <div>
                            <label for="perPage" class="form-label">{{ __('Items per page') }}</label>
                            <select name="perPage" id="perPage" class="form-control" onchange="this.form.submit()">
                                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        {!! $contracts->withQueryString()->links() !!}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
