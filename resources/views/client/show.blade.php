@extends('layouts.app')

@section('template_title')
    {{ $client->name ?? __('Show') . " " . __('Client') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <!-- Client Details Card -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Client</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('clients.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $client->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Contact Details:</strong>
                            {{ $client->contact_details }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects List Card -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Associated Projects') }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        @if($projects->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Empower ID') }}</th>
                                            <th>{{ __('Project Manager') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="text-center">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ $project->empowerID }}</td>
                                                <td>{{ $project->projectManager }}</td>
                                                <td>{{ $project->start_date }}</td>
                                                <td>{{ $project->end_date }}</td>
                                                <td>
                                                    @if(strtolower($project->status) == 'active')
                                                        <span class="badge badge-success">{{ $project->status }}</span>
                                                    @elseif(strtolower($project->status) == 'closed' || strtolower($project->status) == 'inactive')
                                                        <span class="badge badge-secondary">{{ $project->status }}</span>
                                                    @else
                                                        <span class="badge badge-info">{{ $project->status }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info btn-sm" title="{{ __('View Project') }}">
                                                        {{ __('View') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="p-3 d-flex justify-content-center">
                                {{ $projects->links() }}
                            </div>
                        @else
                            <div class="p-3">
                                <p class="text-muted text-center">{{ __('No projects found for this client.') }}</p>
                            </div>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
@endsection