@extends('layouts.app')

@section('template_title')
    Projects
@endsection

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
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search..." style="width: auto;" value="{{ request('search') }}"
                                        onkeydown="if (event.keyCode == 13) { document.getElementById('filterForm').submit(); return false; }">
                                </form>
                                &nbsp;
                                @can('projects.create')<a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                                    {{ __('Create New') }}
                                </a>@endcan
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
                <x-pagination :paginator="$projects" route="projects.index" />
            </div>
        </div>
    </div>
@endsection
