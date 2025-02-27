<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        @if(!isset($user))
        <div class="form-group mb-2 mb20">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
            {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        @endif
        <div class="form-group mb-2 mb20">
            <label for="current_team_id" class="form-label">{{ __('Current Resource Team') }}</label>
            <select name="current_team_id" class="form-control @error('current_team_id') is-invalid @enderror" id="current_team_id">
                <option value="">-- Select --</option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}" {{ old('current_team_id', $user?->current_team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('current_team_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resource_id" class="form-label">{{ __('Linked Resource') }}</label>
            <select name="resource_id" class="form-control @error('resource_id') is-invalid @enderror" id="resource_id">
                <option value="">-- Select --</option>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" {{ old('resource_id', $user?->resource_id) == $resource->id ? 'selected' : '' }}>{{ $resource->full_name }}</option>
                @endforeach
            </select>
            {!! $errors->first('resource_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reports" class="form-label">{{ __('Leader') }}</label>
            <select name="reports" class="form-control @error('reports') is-invalid @enderror" id="reports">
                <option value="">-- Select --</option>
                @foreach($users as $key => $value)
                    <option value="{{ $value->id }}" {{ old('reports', $user?->reports) == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('reports', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>