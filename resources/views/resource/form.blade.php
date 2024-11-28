<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="full_name" class="form-label">{{ __('Full Name') }}</label>
            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $resource?->full_name) }}" id="full_name" placeholder="Full Name">
            {!! $errors->first('full_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="empower_i_d" class="form-label">{{ __('Empowerid') }}</label>
            <input type="text" name="empowerID" class="form-control @error('empowerID') is-invalid @enderror" value="{{ old('empowerID', $resource?->empowerID) }}" id="empower_i_d" placeholder="Empowerid">
            {!! $errors->first('empowerID', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ad_i_d" class="form-label">{{ __('AD id') }}</label>
            <input type="text" name="adID" class="form-control @error('adID') is-invalid @enderror" value="{{ old('adID', $resource?->adID) }}" id="ad_i_d" placeholder="Adid">
            {!! $errors->first('adID', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="location_id" class="form-label">{{ __('Location') }}</label>
            <select name="location_id" class="form-control @error('location_id') is-invalid @enderror" id="location_id" required>
                <option value="">{{ __('Select a Location') }}</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" @if(old('location_id', $resource?->location_id) == $location->id) selected @endif>{{ $location->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('location_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>