<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="full_name" class="form-label">{{ __('Full Name') }}</label>
            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                value="{{ old('full_name', $resource?->full_name) }}" id="full_name" placeholder="Full Name">
            {!! $errors->first('full_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="empower_i_d" class="form-label">{{ __('Empowerid') }}</label>
            <input type="text" name="empowerID" class="form-control @error('empowerID') is-invalid @enderror"
                value="{{ old('empowerID', $resource?->empowerID) }}" id="empower_i_d" placeholder="Empowerid">
            {!! $errors->first('empowerID', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('Linked User') }}</label>
            <div class="input-group">
                <select class="form-control" name="userID" id="user_id">
                    <option value="" disabled {{ isset($resource) && $resource->user_ID === null ? 'selected' : '' }}>
                        {{ isset($resource) && $resource->user_ID === null ? 'Search for a user' : $resource->user->name }}
                    </option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ isset($resource) && $resource->user_ID == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="document.getElementById('user_id').value = '';">
                        <i class="fa fa-times-circle"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-plus"
                        onclick="window.open('{{ route('users.create') }}?name='+document.getElementById('full_name').value+'&resource_type='+document.getElementById('resource_type').value, '_blank')">
                        <i class="fa fa-plus-circle fa-lg"></i>
                    </button>
                </div>
            </div>
            {!! $errors->first('userID', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resource_type" class="form-label">{{ __('Resource Type') }}</label>
            <select name="resource_type" class="form-control @error('resource_type') is-invalid @enderror" id="resource_type" required>
                <option value="">{{ __('Select a Resource Type') }}</option>
                @foreach ($resourceTypes as $type)
                    <option value="{{ $type->id }}" @if(old('resource_type', $resource?->resource_type) == $type->id) selected @endif>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('resource_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="location_id" class="form-label">{{ __('Location') }}</label>
            <select name="location_id" class="form-control @error('location_id') is-invalid @enderror" id="location_id"
                required>
                <option value="">{{ __('Select a Location') }}</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" @if (old('location_id', $resource?->location_id) == $location->id) selected @endif>
                        {{ $location->name }}</option>
                @endforeach
            </select>
            {!! $errors->first('location_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="skills" class="form-label">{{ __('Skills') }}</label>
            <input name="skills" class="form-control @error('skills') is-invalid @enderror" value=""
                id="skills" placeholder="Add skills">
            {!! $errors->first('skills', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
    <!-- Dialog for editing proficiency -->
    <dialog id="editProficiencyDialog" style="width:33vw;min-width:300px;">
        <h3>Edit Proficiency</h3>
        <div>
            <label for="proficiency">Proficiency:</label>
            <select id="proficiency">
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>
        </div>
        <button id="saveProficiency">Save</button>
        <button id="cancelProficiency">Cancel</button>
    </dialog>

    <script>
        $(document).ready(function() {
            // Initialize Tagify
            var input = document.querySelector('#skills');
            var tagify = new Tagify(input, {
                whitelist: @json($skills),
                editTags: {
                    clicks: 2, // single click to edit a tag
                    keepInvalid: false // if after editing, tag is invalid, auto-revert
                },
                enforceWhitelist: true,
                dropdown: {
                    enabled: 0,
                    maxItems: 20,
                    closeOnSelect: true,
                    highlightFirst: true,
                    searchKeys: ["name"]
                },
                templates: {
                    tag: function(tagData, tagify) {
                        return `<tag title="${tagData.name}" contenteditable='false' spellcheck="false" class='tagify__tag ${tagData.class ? tagData.class : ""}' ${this.getAttributes(tagData)}>
                            <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                            <div>
                                <span class='tagify__tag-text'>${tagData.name} - ${tagData.proficiency}</span>
                            </div>
                        </tag>`;
                    },
                    dropdownItem: function(tagData, tagify) {
                        return `<div ${this.getAttributes(tagData)} class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'>
                            ${tagData.name}
                        </div>`;
                    }
                }
            });

            // Pre-fill the Tagify input with existing skills and proficiency
            tagify.addTags([
                @foreach ($resourceSkills as $skill)
                    {
                        value: "{{ $skill['skill']['id'] }}",
                        name: "{{ $skill['skill']['skill_name'] }}",
                        proficiency: "{{ $skill['proficiency_levels'] }}"
                    },
                @endforeach
            ]);

            var clickDebounce;

            tagify.on('click', function(e) {
                const {
                    tag: tagElm,
                    data: tagData
                } = e.detail;

                // a delay is needed to distinguish between regular click and double-click.
                // this allows enough time for a possible double-click, and only fires if such
                // did not occur.
                clearTimeout(clickDebounce);
                clickDebounce = setTimeout(() => {
                    const dialog = document.getElementById('editProficiencyDialog');
                    dialog.querySelector('h3').innerText = `Edit ${tagData.name}`;
                    dialog.querySelector('select').value = tagData.proficiency;
                    dialog.showModal();

                    document.getElementById('saveProficiency').addEventListener('click', function(
                        e) {
                        e.preventDefault();
                        const dialog = document.getElementById('editProficiencyDialog');
                        const selectedProficiency = dialog.querySelector('select').value;
                        tagData.proficiency = selectedProficiency;
                        tagElm.querySelector('.tagify__tag-text').innerText =
                            `${tagData.name} - ${selectedProficiency}`;
                        dialog.close();
                        // Update the tagify value
                        tagify.value = tagify.value.map(tag => {
                            if (tag.value === tagData.value) {
                                return {
                                    value: tag.value,
                                    name: tag.name,
                                    proficiency: tagData.proficiency
                                };
                            }
                            return tag;
                        });
                    });

                    document.getElementById('cancelProficiency').addEventListener('click', function(
                        e) {
                        e.preventDefault();
                        const dialog = document.getElementById('editProficiencyDialog');
                        dialog.close();
                    });

                }, 200);
            });

            // Handle form submission to include proficiency in the skills input
            $('form').on('submit', function(e) {
                e.preventDefault();
                var skillsData = tagify.value.map(tag => {
                    return {
                        id: tag.value,
                        proficiency: tag.proficiency || 'Unknown'
                    };
                });
                //console.log('Tagify Value:', tagify.value);
                //console.log('Skills Data:', skillsData);
                input.value = JSON.stringify(skillsData);
                this.submit(); // Submit the form after setting the value
            });
        });
    </script>

</div>
