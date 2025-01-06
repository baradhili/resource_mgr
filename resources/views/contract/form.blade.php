<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                value="{{ old('start_date', $contract?->start_date ? date('Y-m-d', strtotime($contract->start_date)) : '') }}" id="start_date" placeholder="Start Date">
            {!! $errors->first('start_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                value="{{ old('end_date', $contract?->end_date ? date('Y-m-d', strtotime($contract->end_date)) : '') }}" id="end_date" placeholder="End Date">
            {!! $errors->first('end_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="availability" class="form-label">{{ __('Availability') }}</label>
            <input type="number" name="availability" class="form-control @error('availability') is-invalid @enderror" 
            value="{{ old('availability', $contract?->availability) }}" id="availability" placeholder="Availability"
            min="0" 
        max="1" 
        step="any">
            {!! $errors->first('availability', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resources_id" class="form-label">{{ __('Resource') }}</label>
            <select name="resources_id" class="form-control @error('resources_id') is-invalid @enderror" id="resources_id">
                <option value="">{{ __('Select a Resource') }}</option>
                @foreach ($resources as $resource)
                    <option value="{{ $resource->id }}" @if(old('resources_id', $contract?->resources_id) == $resource->id) selected
                    @endif>{{ $resource->full_name }}</option>
                @endforeach
            </select>
            {!! $errors->first('resource_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>