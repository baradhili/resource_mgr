<div class="row padding-1 p-1">
    <div class="col-md-12">

        <form method="POST" action="{{ route('services.update', $service->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group mb-2 mb20">
                <label for="service_name" class="form-label">{{ __('Service Name') }}</label>
                <input type="text" name="service_name" class="form-control @error('service_name') is-invalid @enderror"
                    value="{{ old('service_name', $service->service_name) }}" id="service_name"
                    placeholder="Service Name">
                {!! $errors->first('service_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="form-group mb-2 mb20">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                    placeholder="Description">{{ old('description', $service->description) }}</textarea>
                {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="form-group mb-2 mb20">
                <label for="required_skills" class="form-label">{{ __('Required Skills') }}</label>
                <input name="required_skills"
                    value="{{ old('required_skills', json_encode(array_map(fn($skill) => ['value' => $skill], $service->required_skills ?? []))) }}"
                    class="form-control @error('required_skills') is-invalid @enderror" id="required_skills"
                    placeholder="Required Skills">
                {!! $errors->first(
                    'required_skills',
                    '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
                ) !!}
            </div>
            <div class="form-group mb-2 mb20">
                <label for="hours_cost" class="form-label">{{ __('Hours Cost') }}</label>
                <input type="text" name="hours_cost" class="form-control @error('hours_cost') is-invalid @enderror"
                    value="{{ old('hours_cost', $service->hours_cost) }}" id="hours_cost" placeholder="Hours Cost">
                {!! $errors->first('hours_cost', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>

            <div class="col-md-12 mt20 mt-2">
                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </form>

    </div>

    <script>
        var input = document.querySelector('input[name="required_skills"]');
        var tagify = new Tagify(input, {
            whitelist: {!! json_encode($skills) !!},
            enforceWhitelist: true,
            dropdown: {
                enabled: 0, // always open dropdown when input is in focus
                maxItems: 10,
                highlightFirst: true,
            }
        });

        // Ensure the form sends the tag values as an array
        var form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            var skills = tagify.value.map(item => item.value);
            input.value = JSON.stringify(skills);
        });

        // Parse initial value if in JSON format
        try {
            var initialSkills = JSON.parse(input.value);
            if (Array.isArray(initialSkills)) {
                tagify.addTags(initialSkills.map(item => item.value));
            }
        } catch (e) {
            console.error('Failed to parse initial skills:', e);
        }
    </script>
</div>
