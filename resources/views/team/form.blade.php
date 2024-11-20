<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="owner_id" class="form-label">{{ __('Owner') }}</label>
            <select name="owner_id" class="form-control @error('owner_id') is-invalid @enderror" id="owner_id">
                <option value="">{{ __('Select an Owner') }}</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if(old('owner_id', $team?->owner_id) == $user->id) selected @endif>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('owner_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $team?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>