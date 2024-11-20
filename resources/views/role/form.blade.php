<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="team_id" class="form-label">{{ __('Team Id') }}</label>
            <input type="text" name="team_id" class="form-control @error('team_id') is-invalid @enderror" value="{{ old('team_id', $role?->team_id) }}" id="team_id" placeholder="Team Id">
            {!! $errors->first('team_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="guard_name" class="form-label">{{ __('Guard Name') }}</label>
            <input type="text" name="guard_name" class="form-control @error('guard_name') is-invalid @enderror" value="{{ old('guard_name', $role?->guard_name) }}" id="guard_name" placeholder="Guard Name">
            {!! $errors->first('guard_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>