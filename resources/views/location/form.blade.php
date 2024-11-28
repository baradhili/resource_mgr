<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $location?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="region_id" class="form-label">{{ __('Region') }}</label>
            <select name="region_id" class="form-control @error('region_id') is-invalid @enderror" id="region_id" required>
                <option value="">{{ __('Select a Region') }}</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}" @if(old('region_id', $location?->region_id) == $region->id) selected @endif>{{ $region->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('region_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>