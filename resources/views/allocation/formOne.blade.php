<div class="row padding-1 p-1">
    <div class="col-md-12">
        <h4>{{ __('Allocation') . ': ' . $allocation->project->name . ', ' . \Carbon\Carbon::parse($allocation->allocation_date)->format('Y M') }}
            </h4>
        <div class="form-group mb-2 mb20">
            <label for="fte" class="form-label">{{ __('Fte') }}</label>
            <input type="text" name="fte" class="form-control @error('fte') is-invalid @enderror"
                value="{{ old('fte', $allocation?->fte) }}" id="fte" placeholder="Fte">
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resources_id" class="form-label">{{ __('Resource') }}</label>
            <select name="resources_id" class="form-control @error('resources_id') is-invalid @enderror"
                id="resources_id">
                <option value="">{{ __('Select a Resource') }}</option>
                @foreach ($resources as $resource)
                    <option value="{{ $resource->id }}" @if (old('resources_id', $allocation?->resources_id) == $resource->id) selected @endif>
                        {{ $resource->full_name }}</option>
                @endforeach
            </select>
            {!! $errors->first('resource_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror"
                value="{{ old('status', $allocation?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Edit Allocation') }}</button>
        <button type="button" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $allocation->id }}').submit();">{{ __('Delete Allocation') }}</button>
        <form id="delete-form-{{ $allocation->id }}" action="{{ route('allocations.destroy', $allocation->id) }}" method="POST" style="display: none;">
            @method('DELETE')
            @csrf
        </form>
    </div>
</div>
