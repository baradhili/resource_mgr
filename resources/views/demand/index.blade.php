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
                                @can('demands.create')
                                    <a href="{{ route('demands.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Create New') }}
                                    </a>
                                @endcan
                                @can('demands.export')
                                    <a href="{{ route('demands.export') }}" class="btn btn-success btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Export') }}
                                    </a>
                                @endcan
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
                                    @foreach ($paginator as $item)
                                        <tr>
                                            <td><a href="{{ route('projects.show', $item['id'] ?? '') }}">{{ $item['empowerID'] ?? '' }}
                                                    - {{ $item['name'] ?? '' }}</a>
                                            </td>
                                            <td>{{ $item['type'] ?? '-' }} </td>
                                            <!-- Populate availability for each month -->
                                            @foreach ($nextTwelveMonths as $month)
                                                @php
                                                    $monthKey =
                                                        $month['year'] .
                                                        '-' .
                                                        str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                    $demandFTE = $item['demands'][$monthKey] ?? '-';
                                                @endphp
                                                <td>{{ $demandFTE }}</td>
                                            @endforeach
                                            <td>
                                                @can('demands.edit')
                                                    <form action="{{ route('demands.edit', $item['id'] ?? '') }}"
                                                        method="GET" style="display: flex;">
                                                        <select name="resource_id"
                                                            class="form-control @error('resource_id') is-invalid @enderror"
                                                            id="resource_id_{{ $item['id'] ?? '' }}"
                                                            onchange="toggleSubmitButton(this)">
                                                            <option value="">Select Resource</option>
                                                            @foreach ($resources as $resource)
                                                                <option value="{{ $resource->id }}">{{ $resource->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            id="assign_button_{{ $item['id'] ?? '' }}" disabled><i
                                                                class="fa fa-fw fa-edit"></i> {{ __('Assign') }}</button>
                                                    </form>
                                                @endcan
                                                <form action="{{ route('demands.destroy', $item['id'] ?? '') }}"
                                                    method="POST">
                                                    @can('demands.editFullDemand')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('demands.editFullDemand', ['project' => $item['id'] ?? '', 'resource_type' => $item['type_name'] ?? '']) }}"><i
                                                                class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('demands.destroy')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                                class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                    @endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $paginator->withQueryString()->links() !!}
            </div>
        </div>
        <script>
            function toggleSubmitButton(selectElement) {
                var selectedValue = selectElement.value;
                var buttonId = selectElement.id.replace('resource_id', 'assign_button');
                var submitButton = document.getElementById(buttonId);

                if (selectedValue !== "") {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            }

            window.onload = function() {
                var selectElements = document.querySelectorAll('select[name="resource_id"]');
                selectElements.forEach(function(selectElement) {
                    toggleSubmitButton(selectElement);
                });
            };
        </script>
    </div>
@endsection
