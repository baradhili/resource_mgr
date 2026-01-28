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
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Active Projects - Solution Architect Allocations') }}
                            </span>
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
                                <span>No active projects with Solution Architect allocations found for the next 4
                                    months.</span>
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
                                                <td class="fw-bold text-nowrap" style="min-width: 180px;">
                                                    {{ $row['project_name'] }}
                                                </td>
                                                <td class="text-nowrap" style="min-width: 150px;">
                                                    {{ $row['resource_name'] }}
                                                </td>
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


