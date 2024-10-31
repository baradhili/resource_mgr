<div class="row padding-1 p-1">
    <!-- <div class="col-md-12"> -->

        <div class="form-group mb-2 mb20">
            <label for="start_year">{{ __('Start Year') }}</label>
            <input type="number" name="start_year" class="form-control @error('start_year') is-invalid @end
                error" id="start_year" placeholder="Start Year" required>
            {!! $errors->first('start_year', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="start_month">{{ __('Start Month') }}</label>

            <input type="number" name="start_month" class="form-control @error('start_month') is-invalid @enderror"
                id="start_month" placeholder="Start Month" min="1" max="12" required>
            {!! $errors->first('start_month', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">

            <label for="end_year">{{ __('End Year') }}</label>
            <input type="number" name="end_year" class="form-control @error('end_year') is-invalid @enderror"
                id="end_year" placeholder="End Year" required>
            {!! $errors->first('end_year', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


        <div class="form-group mb-2 mb20">
            <label for="end_month">{{ __('End Month') }}</label>
            <input type="number" name="end_month" class="form-control @error('end_month') is-invalid @enderror"
                id="end_month" placeholder="End Month" min="1" max="12" required>
            {!! $errors->first('end_month', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>


        <div class="form-group mb-2 mb20">
            <label for="resource_id">{{ __('Resource') }}</label>
            <select name="resource_id" class="form-control @error('resource_id') is-invalid @enderror" id="resource_id"
                required>
                @foreach ($resources as $resource)

                    <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('resource_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="project_id">{{ __('Project') }}</label>
            <select name="project_id" class="form-control @error('project_id') is-invalid @enderror" id="project_id"
                required>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('project_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="fte">{{ __('FTE (%)') }}</label>
            <input type="number" name="fte" class="form-control @error('fte') is-invalid @enderror" id="fte"
                placeholder="FTE (%)" step="0.01" required>
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>