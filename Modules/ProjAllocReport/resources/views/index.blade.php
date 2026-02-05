@extends('layouts.app')

@section('template_title')
    Solution Architect Allocation Report
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <span id="card_title">
                                {{ __('Active Projects - Solution Architect Allocations') }}
                            </span>
                            
                            <!-- Client Filter Form -->
                            <form action="{{ route('projallocreport.index') }}" method="GET" class="d-inline-flex align-items-center">
                                <select name="client_id" id="client_filter" class="form-control form-control-sm" style="width: 200px; margin-right: 5px;">
                                    <option value="">{{ __('All Clients') }}</option>
                                    @if(isset($clients))
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ $selectedClientId == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-filter"></i> {{ __('Filter') }}
                                </button>
                                @if($selectedClientId)
                                    <a href="{{ route('projallocreport.index') }}" class="btn btn-default btn-sm ml-2">
                                        {{ __('Reset') }}
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        @if (!$hasData || empty($rows))
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-info-circle me-2"></i>
                                <span>No active projects with Solution Architect allocations found for the selected criteria.</span>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle" style="font-size: 0.9rem;">
                                    <thead class="table-light fw-bold">
                                        <tr>
                                            @foreach ($headers as $header)
                                                <th class="text-center py-2 px-3 bg-gradient"
                                                    style="background-color: #f8f9fa; border-right: 1px solid #dee2e6;">
                                                    {{ $header }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $row)
                                            <tr class="border-bottom">
                                                <!-- New Client Name Column -->
                                                <td class="text-muted text-nowrap" style="min-width: 150px; font-size: 0.85em;">
                                                    {{ $row['client_name'] }}
                                                </td>

                                                <!-- Project Name Column (Updated Style) -->
                                                <td class="fw-bold text-nowrap" style="min-width: 180px;">
                                                    {{ $row['project_name'] }}
                                                </td>

                                                <!-- Resource Name Column -->
                                                <td class="text-nowrap" style="min-width: 150px;">
                                                    {{ $row['resource_name'] }}
                                                </td>

                                                <!-- FTE Values Loop -->
                                                @foreach ($row['values'] as $val)
                                                    <td class="text-center fw-medium {{ $val !== '0.00' ? 'bg-light-primary text-primary' : 'text-muted opacity-50' }}"
                                                        style="min-width: 70px;">
                                                        {{ $val === '0.00' ? '-' : $val }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection