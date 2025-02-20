<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="allocation_date" class="form-label">{{ __('Allocation Date') }}</label>
            <input type="text" name="allocation_date" class="form-control @error('allocation_date') is-invalid @enderror" value="{{ old('allocation_date', $stagingAllocation?->allocation_date) }}" id="allocation_date" placeholder="Allocation Date">
            {!! $errors->first('allocation_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fte" class="form-label">{{ __('Fte') }}</label>
            <input type="text" name="fte" class="form-control @error('fte') is-invalid @enderror" value="{{ old('fte', $stagingAllocation?->fte) }}" id="fte" placeholder="Fte">
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resources_id" class="form-label">{{ __('Resources Id') }}</label>
            <input type="text" name="resources_id" class="form-control @error('resources_id') is-invalid @enderror" value="{{ old('resources_id', $stagingAllocation?->resources_id) }}" id="resources_id" placeholder="Resources Id">
            {!! $errors->first('resources_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="projects_id" class="form-label">{{ __('Projects Id') }}</label>
            <input type="text" name="projects_id" class="form-control @error('projects_id') is-invalid @enderror" value="{{ old('projects_id', $stagingAllocation?->projects_id) }}" id="projects_id" placeholder="Projects Id">
            {!! $errors->first('projects_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $stagingAllocation?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="source" class="form-label">{{ __('Source') }}</label>
            <input type="text" name="source" class="form-control @error('source') is-invalid @enderror" value="{{ old('source', $stagingAllocation?->source) }}" id="source" placeholder="Source">
            {!! $errors->first('source', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>