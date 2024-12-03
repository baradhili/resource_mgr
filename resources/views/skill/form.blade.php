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
            <label for="context" class="form-label">{{ __('Context') }}</label>
            <input type="text" name="context" class="form-control @error('context') is-invalid @enderror" value="{{ old('context', $skill?->context) }}" id="context" placeholder="Context">
            {!! $errors->first('context', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="employers" class="form-label">{{ __('Employers') }}</label>
            <input type="text" name="employers" class="form-control @error('employers') is-invalid @enderror" value="{{ old('employers', $skill?->employers) }}" id="employers" placeholder="Employers">
            {!! $errors->first('employers', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="keywords" class="form-label">{{ __('Keywords') }}</label>
            <input type="text" name="keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords', $skill?->keywords) }}" id="keywords" placeholder="Keywords">
            {!! $errors->first('keywords', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="category" class="form-label">{{ __('Category') }}</label>
            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $skill?->category) }}" id="category" placeholder="Category">
            {!! $errors->first('category', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="certifications" class="form-label">{{ __('Certifications') }}</label>
            <input type="text" name="certifications" class="form-control @error('certifications') is-invalid @enderror" value="{{ old('certifications', $skill?->certifications) }}" id="certifications" placeholder="Certifications">
            {!! $errors->first('certifications', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="occupations" class="form-label">{{ __('Occupations') }}</label>
            <input type="text" name="occupations" class="form-control @error('occupations') is-invalid @enderror" value="{{ old('occupations', $skill?->occupations) }}" id="occupations" placeholder="Occupations">
            {!! $errors->first('occupations', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="license" class="form-label">{{ __('License') }}</label>
            <input type="text" name="license" class="form-control @error('license') is-invalid @enderror" value="{{ old('license', $skill?->license) }}" id="license" placeholder="License">
            {!! $errors->first('license', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="derived_from" class="form-label">{{ __('Derived From') }}</label>
            <input type="text" name="derived_from" class="form-control @error('derived_from') is-invalid @enderror" value="{{ old('derived_from', $skill?->derived_from) }}" id="derived_from" placeholder="Derived From">
            {!! $errors->first('derived_from', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="source_id" class="form-label">{{ __('Source Id') }}</label>
            <input type="text" name="source_id" class="form-control @error('source_id') is-invalid @enderror" value="{{ old('source_id', $skill?->source_id) }}" id="source_id" placeholder="Source Id">
            {!! $errors->first('source_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="type" class="form-label">{{ __('Type') }}</label>
            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $skill?->type) }}" id="type" placeholder="Type">
            {!! $errors->first('type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="authors" class="form-label">{{ __('Authors') }}</label>
            <input type="text" name="authors" class="form-control @error('authors') is-invalid @enderror" value="{{ old('authors', $skill?->authors) }}" id="authors" placeholder="Authors">
            {!! $errors->first('authors', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>