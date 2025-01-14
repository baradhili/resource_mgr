<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="demand_date" class="form-label">{{ __('Demand Date') }}</label>
            <input type="text" name="demand_date" class="form-control @error('demand_date') is-invalid @enderror" value="{{ old('demand_date', $stagingDemand?->demand_date) }}" id="demand_date" placeholder="Demand Date">
            {!! $errors->first('demand_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fte" class="form-label">{{ __('Fte') }}</label>
            <input type="text" name="fte" class="form-control @error('fte') is-invalid @enderror" value="{{ old('fte', $stagingDemand?->fte) }}" id="fte" placeholder="Fte">
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $stagingDemand?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resource_type" class="form-label">{{ __('Resource Type') }}</label>
            <input type="text" name="resource_type" class="form-control @error('resource_type') is-invalid @enderror" value="{{ old('resource_type', $stagingDemand?->resource_type) }}" id="resource_type" placeholder="Resource Type">
            {!! $errors->first('resource_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="projects_id" class="form-label">{{ __('Projects Id') }}</label>
            <input type="text" name="projects_id" class="form-control @error('projects_id') is-invalid @enderror" value="{{ old('projects_id', $stagingDemand?->projects_id) }}" id="projects_id" placeholder="Projects Id">
            {!! $errors->first('projects_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="source" class="form-label">{{ __('Source') }}</label>
            <input type="text" name="source" class="form-control @error('source') is-invalid @enderror" value="{{ old('source', $stagingDemand?->source) }}" id="source" placeholder="Source">
            {!! $errors->first('source', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>