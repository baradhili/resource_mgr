<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="service_name" class="form-label">{{ __('Service Name') }}</label>
            <input type="text" name="service_name" class="form-control @error('service_name') is-invalid @enderror" value="{{ old('service_name', $service?->service_name) }}" id="service_name" placeholder="Service Name">
            {!! $errors->first('service_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $service?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="required_skills" class="form-label">{{ __('Required Skills') }}</label>
            <input type="text" name="required_skills" class="form-control @error('required_skills') is-invalid @enderror" value="{{ old('required_skills', $service?->required_skills) }}" id="required_skills" placeholder="Required Skills">
            {!! $errors->first('required_skills', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="hours_cost" class="form-label">{{ __('Hours Cost') }}</label>
            <input type="text" name="hours_cost" class="form-control @error('hours_cost') is-invalid @enderror" value="{{ old('hours_cost', $service?->hours_cost) }}" id="hours_cost" placeholder="Hours Cost">
            {!! $errors->first('hours_cost', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>