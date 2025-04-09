@extends('layouts.app')

@section('template_title')
    Org Report
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Tenures') }}
                            </span>

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
                                        <th>Resource</th>
                                        <th>Empower ID</th>
                                        <th>Role</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Supervisor</th>
                                        <th>Tenure</th>
                                        <th>Projects overlapping end date</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resources as $resource)
                                        <tr>
                                            <td>{{ $resource->full_name }}</td>
                                            <td>{{ $resource->empowerID }}</td>
                                            <td>{{ $resource->resource_type_obj->name }}</td>
                                            <td>{{ optional($resource->contracts->first())->start_date ? \Carbon\Carbon::parse($resource->contracts->first()->start_date)->format('d/m/Y') : '' }}
                                            </td>
                                            <td>{{ optional($resource->contracts->first())->end_date ? \Carbon\Carbon::parse($resource->contracts->first()->end_date)->format('d/m/Y') : '' }}
                                            </td>
                                            <td>{{ $resource->user->reports_to->name ?? '' }}</td>
                                            <td>{{ $resource->tenure }}</td>
                                            <td>{{ $resource->currentProjects ? collect($resource->currentProjects)->map(fn($p) => $p->empowerID . ' - ' . $p->name . ' (' . $p->projectManager . ')')->implode(', ') : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
