<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="team_id" class="form-label">{{ __('Team Id') }}</label>
            <input type="text" name="team_id" class="form-control @error('team_id') is-invalid @enderror" value="{{ old('team_id', $ability?->team_id) }}" id="team_id" placeholder="Team Id">
            {!! $errors->first('team_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $ability?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $ability?->title) }}" id="title" placeholder="Title">
            {!! $errors->first('title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="entity_type" class="form-label">{{ __('Entity Type') }}</label>
            <input type="text" name="entity_type" class="form-control @error('entity_type') is-invalid @enderror" value="{{ old('entity_type', $ability?->entity_type) }}" id="entity_type" placeholder="Entity Type">
            {!! $errors->first('entity_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="entity_id" class="form-label">{{ __('Entity Id') }}</label>
            <input type="text" name="entity_id" class="form-control @error('entity_id') is-invalid @enderror" value="{{ old('entity_id', $ability?->entity_id) }}" id="entity_id" placeholder="Entity Id">
            {!! $errors->first('entity_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>