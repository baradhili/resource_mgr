<div class="row padding-1 p-1">
    <div class="col-md-12">
        
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

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>