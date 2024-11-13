<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="skill_name" class="form-label">{{ __('Skill Name') }}</label>
            <input type="text" name="skill_name" class="form-control @error('skill_name') is-invalid @enderror" value="{{ old('skill_name', $skill?->skill_name) }}" id="skill_name" placeholder="Skill Name">
            {!! $errors->first('skill_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="skill_description" class="form-label">{{ __('Skill Description') }}</label>
            <input type="text" name="skill_description" class="form-control @error('skill_description') is-invalid @enderror" value="{{ old('skill_description', $skill?->skill_description) }}" id="skill_description" placeholder="Skill Description">
            {!! $errors->first('skill_description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sfia_code" class="form-label">{{ __('Sfia Code') }}</label>
            <input type="text" name="sfia_code" class="form-control @error('sfia_code') is-invalid @enderror" value="{{ old('sfia_code', $skill?->sfia_code) }}" id="sfia_code" placeholder="Sfia Code">
            {!! $errors->first('sfia_code', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sfia_level" class="form-label">{{ __('Sfia Level') }}</label>
            <input type="text" name="sfia_level" class="form-control @error('sfia_level') is-invalid @enderror" value="{{ old('sfia_level', $skill?->sfia_level) }}" id="sfia_level" placeholder="Sfia Level">
            {!! $errors->first('sfia_level', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>