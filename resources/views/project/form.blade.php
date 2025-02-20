<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
            <input type="text" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $project?->start_date) }}" id="start_date" placeholder="Start Date">
            {!! $errors->first('start_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
            <input type="text" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $project?->end_date) }}" id="end_date" placeholder="End Date">
            {!! $errors->first('end_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="empower_i_d" class="form-label">{{ __('Empowerid') }}</label>
            <input type="text" name="empowerID" class="form-control @error('empowerID') is-invalid @enderror" value="{{ old('empowerID', $project?->empowerID) }}" id="empower_i_d" placeholder="Empowerid">
            {!! $errors->first('empowerID', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $project?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="project_manager" class="form-label">{{ __('Projectmanager') }}</label>
            <input type="text" name="projectManager" class="form-control @error('projectManager') is-invalid @enderror" value="{{ old('projectManager', $project?->projectManager) }}" id="project_manager" placeholder="Projectmanager">
            {!! $errors->first('projectManager', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $project?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>