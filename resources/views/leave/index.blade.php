@extends('layouts.app')

@section('template_title')
Leaves
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            {{ __('Leaves') }}
                        </span>

                        <div class="float-right d-flex align-items-center">
                            <form action="{{ route('leaves.index') }}" method="get" class="d-inline-flex align-items-center" id="filterForm">
                                <div class="custom-control custom-checkbox mr-2">
                                    <input type="checkbox" class="custom-control-input" id="showOld" name="old" value="1" {{ request('old') == 1 ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <label class="custom-control-label" for="showOld">Show Old</label>
                                </div>
                                &nbsp;
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search..." style="width: auto;" value="{{ request('search') }}" onkeydown="if (event.keyCode == 13) { document.getElementById('filterForm').submit(); return false; }">
                            </form>
                            &nbsp;
                            @can('leaves.create')<a href="{{ route('leaves.create') }}" class="btn btn-primary btn-sm">
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
                                    <th>Resource</th>

                                    <th>Start Date</th>
                                    <th>End Date</th>


                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->resource->full_name }}</td>

                                        <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') }}</td>


                                        <td>
                                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST">
                                                <!-- <a class="btn btn-sm btn-primary "
                                                    href="{{ route('leaves.show', $leave->id) }}"><i
                                                        class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a> -->
                                                @can('leaves.edit')<a class="btn btn-sm btn-success"
                                                    href="{{ route('leaves.edit', $leave->id) }}"><i
                                                        class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>@endcan
                                                @csrf
                                                @method('DELETE')
                                                @can('leaves.destroy')<button type="submit" class="btn btn-danger btn-sm"
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
            @include('partials.pagination', ['paginator' => $leaves, 'route' => 'leaves.index'])
        </div>
    </div>
</div>
@endsection