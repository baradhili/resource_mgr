<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="projects_id" class="form-label">{{ __('Projects') }}</label>
            <select name="projects_id" class="form-control @error('projects_id') is-invalid @enderror" id="projects_id">
                <option value="">{{ __('Select a Project') }}</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ old('projects_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->name }} ({{ $project->empowerID }})
                    </option>
                @endforeach 
            </select>
            {!! $errors->first('projects_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                value="{{ old('start_date', $demand?->start_date) }}" id="start_date" placeholder="Start Date">
            {!! $errors->first('start_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                value="{{ old('end_date', $demand?->end_date) }}" id="end_date" placeholder="End Date">
            {!! $errors->first('end_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror"
                value="{{ old('status', $demand?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="resource_type" class="form-label">{{ __('Resource Type') }}</label>
            <input type="text" name="resource_type" class="form-control @error('resource_type') is-invalid @enderror"
                value="{{ old('resource_type', $demand?->resource_type) }}" id="resource_type"
                placeholder="Resource Type">
            {!! $errors->first(
                'resource_type',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="fte" class="form-label">{{ __('FTE') }}</label>
            <input type="number" step="0.01" name="fte" class="form-control @error('fte') is-invalid @enderror"
                value="{{ old('fte', $demand?->fte) }}" id="fte" placeholder="FTE">
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
