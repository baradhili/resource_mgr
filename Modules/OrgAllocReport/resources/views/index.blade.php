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
                        @if ($projectAllocations->isEmpty())
                            <div class="alert alert-info">
                                <p>No active projects with Solution Architect allocations found for the selected period.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead">
                                        <tr>
                                            <th>Project</th>
                                            <th>Project Manager</th>
                                            <th>Resource Name</th>
                                            @foreach ($months as $month)
                                                <th class="text-center">{{ $month }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $lastProjectId = null; @endphp
                                        @foreach ($projectAllocations as $allocation)
                                            <tr>
                                                @if ($lastProjectId !== $allocation['project_id'])
                                                    <td>
                                                        <strong>{{ $allocation['project_empower_id'] }}</strong><br>
                                                        <small>{{ $allocation['project_name'] }}</small>
                                                    </td>
                                                    <td>{{ $allocation['project_manager'] }}</td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                                <td>{{ $allocation['resource_name'] }}</td>
                                                @foreach ($allocation['monthly_allocations'] as $monthAlloc)
                                                    <td class="text-center">
                                                        @if ($monthAlloc !== null)
                                                            {{ number_format($monthAlloc, 2) }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                            @php $lastProjectId = $allocation['project_id']; @endphp
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
