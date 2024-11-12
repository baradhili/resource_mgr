<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="resources_id" class="form-label">{{ __('Resources') }}</label>
            @if($resource->id != 0)
                <input type="text" class="form-control" value="{{ $resource->full_name }}" readonly>
                <input type="hidden" name="resources_id" value="{{ $resource->id }}">
            @else
                <select name="resources_id" class="form-control @error('resources_id') is-invalid @enderror" id="resources_id">
                    <option value="">{{ __('Select a Resource') }}</option>
                    @foreach ($resources as $res)
                        <option value="{{ $res->id }}" @if(old('resources_id', $resourceSkill?->resources_id) == $res->id) selected @endif>{{ $res->full_name }}</option>
                    @endforeach
                </select>
            @endif
            {!! $errors->first('resources_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="skills_id" class="form-label">{{ __('Skills') }}</label>
            <select name="skills_id" class="form-control @error('skills_id') is-invalid @enderror" id="skills_id">
                <option value="">{{ __('Select a Skill') }}</option>
                @foreach ($unassignedSkills as $skill)
                    <option value="{{ $skill->id }}" @if(old('skills_id', $resourceSkill?->skills_id) == $skill->id) selected @endif>{{ $skill->skill_name }}</option>
                @endforeach
            </select>
            {!! $errors->first('skills_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="proficiency_levels" class="form-label">{{ __('Proficiency Levels') }}</label>
            <select name="proficiency_levels" class="form-control @error('proficiency_levels') is-invalid @enderror" id="proficiency_levels">
                <option value="">{{ __('Select Proficiency Level') }}</option>
                <option value="Beginner" @if(old('proficiency_levels', $resourceSkill?->proficiency_levels) == 'Beginner') selected @endif>Beginner</option>
                <option value="Intermediate" @if(old('proficiency_levels', $resourceSkill?->proficiency_levels) == 'Intermediate') selected @endif>Intermediate</option>
                <option value="Advanced" @if(old('proficiency_levels', $resourceSkill?->proficiency_levels) == 'Advanced') selected @endif>Advanced</option>
                <option value="Expert" @if(old('proficiency_levels', $resourceSkill?->proficiency_levels) == 'Expert') selected @endif>Expert</option>
            </select>
            {!! $errors->first('proficiency_levels', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>