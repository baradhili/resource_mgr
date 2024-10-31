<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="start_year" class="form-label">{{ __('Start Year') }}</label>
            <input type="text" name="start_year" class="form-control @error('year') is-invalid @enderror" value="{{ old('start_year', $allocation?->year) }}" id="year" placeholder="Year">
            {!! $errors->first('start_year', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_month" class="form-label">{{ __('Start Month') }}</label>
            <input type="text" name="start_month" class="form-control @error('month') is-invalid @enderror" value="{{ old('start_month', $allocation?->month) }}" id="month" placeholder="Month">
            {!! $errors->first('start_month', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_year" class="form-label">{{ __('End Year') }}</label>
            <input type="text" name="end_year" class="form-control @error('year') is-invalid @enderror" value="{{ old('end_year', $allocation?->year) }}" id="year" placeholder="Year">
            {!! $errors->first('end_year', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_month" class="form-label">{{ __('End Month') }}</label>
            <input type="text" name="end_month" class="form-control @error('month') is-invalid @enderror" value="{{ old('end_month', $allocation?->month) }}" id="month" placeholder="Month">
            {!! $errors->first('end_month', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fte" class="form-label">{{ __('Fte') }}</label>
            <input type="text" name="fte" class="form-control @error('fte') is-invalid @enderror" value="{{ old('fte', $allocation?->fte) }}" id="fte" placeholder="Fte">
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resources_id" class="form-label">{{ __('Resource') }}</label>
            <select name="resources_id" class="form-control @error('resources_id') is-invalid @enderror" id="resources_id">
                <option value="">{{ __('Select a Resource') }}</option>
                @foreach ($resources as $resource)
                    <option value="{{ $resource->id }}" @if(old('resources_id', $allocation?->resources_id) == $resource->id) selected
                    @endif>{{ $resource->full_name }}</option>
                @endforeach
            </select>
            {!! $errors->first('resource_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="projects_id" class="form-label">{{ __('Resource') }}</label>
            <select name="projects_id" class="form-control @error('projects_id') is-invalid @enderror" id="reso
        u       rces_id">
                <option value="">{{ __('Select a Project') }}</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @if(old('projects_id', $allocation?->projects_id) == $project->id) selected
                    @endif>{{ $project->empowerID . " " . $project->name }}</option>
                @endforeach

                           </select>
            {!! $errors->first('resource_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $allocation?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>