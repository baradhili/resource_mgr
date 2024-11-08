@extends('layouts.app')

@section('template_title')
    Demands
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Demands') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('demands.create') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Create New') }}
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
                                        <th>Project</th>
                                        <th>Type</th>
                                        <!-- Dynamically add columns for the next twelve months -->
                                        @foreach ($nextTwelveMonths as $month)
                                            <th>{{ $month['monthName'] }} {{ $month['year'] }}</th>
                                        @endforeach
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $demandArray[$project['id']]['type'] ?? '-' }} </td>
                                            <!-- Populate availability for each month -->
                                            @foreach ($nextTwelveMonths as $month)
                                                @php
                                                    $monthKey =
                                                        $month['year'] .
                                                        '-' .
                                                        str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                    $demandFTE =
                                                        $demandArray[$project['id']]['demand'][$monthKey] ?? '-';
                                                @endphp
                                                <td>{{ $demandFTE }}</td>
                                            @endforeach
                                            <td>
                                                <form action="{{ route('demands.edit', $project->id) }}" method="GET"
                                                    style="display: flex;"><select name="resource_id"
                                                        class="form-control @error('resource_id') is-invalid @enderror"
                                                        id="resource_id">
                                                        <option value="">Select Resource</option>
                                                        @foreach ($resources as $resource)
                                                            <option value="{{ $resource->id }}">{{ $resource->full_name }}
                                                            </option>
                                                        @endforeach
                                                    </select><button type="submit" class="btn btn-sm btn-success"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Assign') }}</button>
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
