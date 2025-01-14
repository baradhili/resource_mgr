<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $team?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resource_type" class="form-label">{{ __('Resource Type') }}</label>
            <select name="resource_type" class="form-control @error('resource_type') is-invalid @enderror" id="resource_type">
                <option value="">{{ __('Select a Resource Type') }}</option>
                @foreach ($resource_types as $resource_type)
                    <option value="{{ $resource_type['name'] }}"
                        @if(old('resource_type', $team?->resource_type) == $resource_type['name']) selected @endif>
                        {{ $resource_type['name'] }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first(
                'resource_type',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="members" class="form-label">{{ __('Members') }}</label>
            <input name="members" class="form-control @error('members') is-invalid @enderror" value=""
                id="members" placeholder="Members">
            {!! $errors->first('members', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
    <script>
        var input = document.querySelector('input[name=members]');
        var tagify = new Tagify(input, {
            whitelist: @json($users),
            enforceWhitelist: true,
            dropdown: {
                enabled: 0, // always open dropdown when input is in focus
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
                                <span class='tagify__tag-text'>${tagData.name}</span>
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
            @if (!empty($team->members))
                @foreach ($team->members as $member)
                    {
                        value: "{{ $member['id'] }}",
                        name: "{{ $member['name'] }}"
                    },
                @endforeach
            @endif
        ]);
    </script>
</div>
