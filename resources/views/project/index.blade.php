@extends('layouts.app')

@section('template_title')
    Projects
@endsection

<?php
// Fetch clients for the filter dropdown.
// We use 'name' as both key and value to match the controller's search logic on client names.
 $clients = \App\Models\Client::orderBy('name')->pluck('name', 'name');
?>

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Projects') }}
                            </span>

                            <div class="float-right d-flex align-items-center">
                                <form action="{{ route('projects.index') }}" method="get"
                                    class="d-inline-flex align-items-center" id="filterForm">
                                    
                                    <!-- Text Search -->
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" class="form-control" id="search" name="search"
                                            placeholder="Search Name, ID..." value="{{ request('search') }}"
                                            onkeydown="if (event.keyCode == 13) { document.getElementById('filterForm').submit(); return false; }">
                                        
                                        <!-- Client Filter Dropdown -->
                                        <div class="input-group-append">
                                            <select class="form-control" id="client_filter" onchange="filterByClient()"
                                                style="max-width: 150px; border-left: 0;">
                                                <option value="">Filter by Client...</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client }}" {{ request('search') == $client ? 'selected' : '' }}>
                                                        {{ $client }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    &nbsp;
                                    @can('projects.create')<a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                                        {{ __('Create New') }}
                                    </a>@endcan
                                </form>
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
                                        <th>No</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Empowerid</th>
                                        <th>Name</th>
                                        <th>Client</th> <!-- New Column -->
                                        <th>Projectmanager</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $project->start_date }}</td>
                                            <td>{{ $project->end_date }}</td>
                                            <td>{{ $project->empowerID }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->client->name ?? '-' }}</td> <!-- Client Name Display -->
                                            <td>{{ $project->projectManager }}</td>
                                            <td>{{ $project->status }}</td>

                                            <td>
                                                <form action="{{ route('projects.destroy', $project->id) }}"
                                                    method="POST">
                                                    @can('projects.show')<a class="btn btn-sm btn-primary "
                                                        href="{{ route('projects.show', $project->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>@endcan
                                                    @can('projects.edit')<a class="btn btn-sm btn-success"
                                                        href="{{ route('projects.edit', $project->id) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>@endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('projects.destroy')<button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                            class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>@endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @include('partials.pagination', ['paginator' => $projects, 'route' => 'projects.index'])
            </div>
        </div>
    </div>

    <script>
        function filterByClient() {
            var select = document.getElementById('client_filter');
            var searchInput = document.getElementById('search');
            
            // If a client is selected, populate the search input and submit
            if (select.value) {
                searchInput.value = select.value;
                document.getElementById('filterForm').submit();
            } else {
                // If the user selects the "Filter by Client..." empty option, clear search and submit
                searchInput.value = '';
                document.getElementById('filterForm').submit();
            }
        }
    </script>
@endsection