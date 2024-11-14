<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="team_id" class="form-label">{{ __('Team Id') }}</label>
            <input type="text" name="team_id" class="form-control @error('team_id') is-invalid @enderror" value="{{ old('team_id', $permission?->team_id) }}" id="team_id" placeholder="Team Id">
            {!! $errors->first('team_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ability_id" class="form-label">{{ __('Ability Id') }}</label>
            <input type="text" name="ability_id" class="form-control @error('ability_id') is-invalid @enderror" value="{{ old('ability_id', $permission?->ability_id) }}" id="ability_id" placeholder="Ability Id">
            {!! $errors->first('ability_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="entity_type" class="form-label">{{ __('Entity Type') }}</label>
            <input type="text" name="entity_type" class="form-control @error('entity_type') is-invalid @enderror" value="{{ old('entity_type', $permission?->entity_type) }}" id="entity_type" placeholder="Entity Type">
            {!! $errors->first('entity_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="entity_id" class="form-label">{{ __('Entity Id') }}</label>
            <input type="text" name="entity_id" class="form-control @error('entity_id') is-invalid @enderror" value="{{ old('entity_id', $permission?->entity_id) }}" id="entity_id" placeholder="Entity Id">
            {!! $errors->first('entity_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="forbidden" class="form-label">{{ __('Forbidden') }}</label>
            <input type="text" name="forbidden" class="form-control @error('forbidden') is-invalid @enderror" value="{{ old('forbidden', $permission?->forbidden) }}" id="forbidden" placeholder="Forbidden">
            {!! $errors->first('forbidden', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>