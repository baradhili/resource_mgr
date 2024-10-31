<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="year" class="form-label">{{ __('Year') }}</label>
            <input type="text" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $demand?->year) }}" id="year" placeholder="Year">
            {!! $errors->first('year', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="month" class="form-label">{{ __('Month') }}</label>
            <input type="text" name="month" class="form-control @error('month') is-invalid @enderror" value="{{ old('month', $demand?->month) }}" id="month" placeholder="Month">
            {!! $errors->first('month', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fte" class="form-label">{{ __('Fte') }}</label>
            <input type="text" name="fte" class="form-control @error('fte') is-invalid @enderror" value="{{ old('fte', $demand?->fte) }}" id="fte" placeholder="Fte">
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $demand?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="projects_id" class="form-label">{{ __('Projects Id') }}</label>
            <input type="text" name="projects_id" class="form-control @error('projects_id') is-invalid @enderror" value="{{ old('projects_id', $demand?->projects_id) }}" id="projects_id" placeholder="Projects Id">
            {!! $errors->first('projects_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>