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
                                <a href="{{ route('demands.export') }}" class="btn btn-success btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Export') }}
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
                                            <td>{{ $project->empowerID ?? '' }} - {{ $project->name ?? '' }}</td>
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
                                            <form action="{{ route('demands.edit', $project->id) }}" method="GET" style="display: flex;">
    <select name="resource_id" class="form-control @error('resource_id') is-invalid @enderror" id="resource_id_{{ $project->id }}" onchange="toggleSubmitButton(this)">
        <option value="">Select Resource</option>
        @foreach ($resources as $resource)
            <option value="{{ $resource->id }}">{{ $resource->full_name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-sm btn-success" id="assign_button_{{ $project->id }}" disabled><i class="fa fa-fw fa-edit"></i> {{ __('Assign') }}</button>
</form>
                                                <form action="{{ route('demands.destroy', $project->id) }}" method="POST">
                                                <a class="btn btn-sm btn-primary "
                                                    href="{{ route('demands.show', $project->id) }}"><i
                                                        class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('demands.editFullDemand', $project->id) }}"><i
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
        <script>
function toggleSubmitButton(selectElement) {
    var selectedValue = selectElement.value;
    var buttonId = selectElement.id.replace('resource_id', 'assign_button');
    var submitButton = document.getElementById(buttonId);
    
    // Check if a valid resource is selected
    if (selectedValue !== "") {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}

// Optionally, call the function on page load to ensure the button is initially disabled
window.onload = function() {
    var selectElements = document.querySelectorAll('select[name="resource_id"]');
    selectElements.forEach(function(selectElement) {
        toggleSubmitButton(selectElement);
    });
};
</script>
    </div>
@endsection
