@extends('layouts.app')

@section('template_title')
    Org Allocation Report
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
                                        <th>Team Member</th>
                                        <th>PG/Function</th>
                                        <th>Project</th>
                                        <th>Current Focus</th>
                                        <th>Status</th>
                                        <th>Allocation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resources as $resource)
                                        @foreach ($resource->currentProjects as $project)
                                            @if ($project->allocatedThisMonth > 0)
                                                <tr>
                                                    <td>{{ $resource->full_name }}</td>
                                                    <td>~</td>
                                                    <td>{{ $project->empowerID . ' - ' . $project->name  }}</td>
                                                    <td>~</td>
                                                    <td>Active</td>
                                                    <td>{{  $project->allocatedThisMonth }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
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
