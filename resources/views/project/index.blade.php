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

                            <div
                                style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 0.5em;">
                                <form action="{{ route('projects.search') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search"
                                            value="{{ request()->query('search') }}" placeholder="{{ __('Search') }}"
                                            aria-label="{{ __('Search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit" data-toggle="tooltip"
                                                data-placement="top" title="{{ __('Search') }}" aria-label="{{ __('Search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            @if (request()->query('search'))
                                                <a href="{{ route('projects.index') }}" class="btn btn-secondary"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ __('Clear search') }}" aria-label="{{ __('Clear search') }}">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm"
                                    style="display: flex; justify-content: center; align-items: center; border-radius: 50%; width: 35px; height: 35px;"
                                    data-toggle="tooltip" data-placement="left" title="{{ __('Create new project') }}"
                                    aria-label="{{ __('Create new project') }}">
                                    <i class="fas fa-plus"></i>
                                </a>
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


                                        <th>Empowerid</th>
                                        <th>Name</th>
                                        <th>Project Manager</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>


                                            <td>{{ $project->empowerID }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->projectManager }}</td>

                                            <td>
                                                <form action="{{ route('projects.destroy', $project->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('projects.show', $project->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('projects.edit', $project->id) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                            class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $projects->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
