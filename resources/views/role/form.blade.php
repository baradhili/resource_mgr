<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $role?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="role_permissions" class="form-label">{{ __('Permissions') }}</label>
            <input id="role_permissions" name="role_permissions"
                class="form-control @error('role_permissions') is-invalid @enderror"
                value="{{ old('role_permissions', implode(',', $role_permissions->pluck('name')->toArray())) }}"
                type="text">
            {!! $errors->first(
                'role_permissions',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var permissionTags = new Tagify(document.getElementById("role_permissions"), {
                    whitelist: [
                        @foreach ($permissions as $permission)
                            "{{ $permission->name }}",
                        @endforeach
                    ],
                    originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
                    dropdown: {
                        enabled: 0,
                        classname: "tagify--dropdown__permissions",
                    },
                    enforceWhitelist: true, // Ensure only items from the whitelist can be added
                });

                // Set the initial value using the Tagify method
                permissionTags.addTags(
                    @json($role_permissions->pluck('name')->toArray())
                );
            });
        </script>




    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
