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
            <label for="ad_i_d" class="form-label">{{ __('AD id') }}</label>
            <input type="text" name="adID" class="form-control @error('adID') is-invalid @enderror"
                value="{{ old('adID', $resource?->adID) }}" id="ad_i_d" placeholder="Adid">
            {!! $errors->first('adID', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
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
            <table id="skills_table" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Skill Name</th>
                        <th>Proficiency</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resourceSkills as $skill)
                        <tr>
                            <td>
                                <select class="form-control" name="skills[{{ $skill['id'] }}]" disabled>
                                    @foreach ($skills as $s)
                                        <option value="{{ $s->id }}" {{ $s->id == $skill['id'] ? 'selected' : '' }}>
                                            {{ $s->skill_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="proficiencies[{{ $skill['id'] }}]" disabled>
                                    <option value="Beginner"
                                        {{ $skill['proficiency_levels'] == 'Beginner' ? 'selected' : '' }}>Beginner
                                    </option>
                                    <option value="Intermediate"
                                        {{ $skill['proficiency_levels'] == 'Intermediate' ? 'selected' : '' }}>
                                        Intermediate</option>
                                    <option value="Advanced"
                                        {{ $skill['proficiency_levels'] == 'Advanced' ? 'selected' : '' }}>Advanced
                                    </option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-secondary edit-skills-btn" data-id="{{ $skill['id'] }}">
                                    <i data-feather="edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary d-none save-skills-btn" data-id="{{ $skill['id'] }}">
                                    <i data-feather="save"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger d-none cancel-skills-btn" data-id="{{ $skill['id'] }}">
                                    <i data-feather="x"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-skills-btn" data-id="{{ $skill['id'] }}">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12 mt20 mt-2">
            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
        <script>
            $(document).ready(function() {
                $('#skills_table').DataTable({
                    // You can add DataTables options here, such as column definitions, searching, pagination, etc.
                    // For example:
                    // "paging": true,
                    // "searching": true,
                    // "ordering": true,
                    // "info": true,
                    "columnDefs": [{ "targets": [2], "orderable": false }]
                });
                $('.edit-skills-btn').on('click', function() {
                    var id = $(this).data('id');
                    $(`#skills_table tbody tr input[name="skills[${id}]"]`).prop('disabled', false);
                    $(`#skills_table tbody tr select[name="proficiencies[${id}]"]`).prop('disabled', false);
                    $(`.edit-skills-btn[data-id="${id}"]`).addClass('d-none');
                    $(`.save-skills-btn[data-id="${id}"]`).removeClass('d-none');
                    $(`.cancel-skills-btn[data-id="${id}"]`).removeClass('d-none');
                });
                $('.save-skills-btn').on('click', function() {
                    var id = $(this).data('id');
                    $(`#skills_table tbody tr input[name="skills[${id}]"]`).prop('disabled', true);
                    $(`#skills_table tbody tr select[name="proficiencies[${id}]"]`).prop('disabled', true);
                    $(`.edit-skills-btn[data-id="${id}"]`).removeClass('d-none');
                    $(`.save-skills-btn[data-id="${id}"]`).addClass('d-none');
                    $(`.cancel-skills-btn[data-id="${id}"]`).addClass('d-none');
                });
                $('.cancel-skills-btn').on('click', function() {
                    var id = $(this).data('id');
                    $(`#skills_table tbody tr input[name="skills[${id}]"]`).prop('disabled', true);
                    $(`#skills_table tbody tr select[name="proficiencies[${id}]"]`).prop('disabled', true);
                    $(`.edit-skills-btn[data-id="${id}"]`).removeClass('d-none');
                    $(`.save-skills-btn[data-id="${id}"]`).addClass('d-none');
                    $(`.cancel-skills-btn[data-id="${id}"]`).addClass('d-none');
                });
                $('.delete-skills-btn').on('click', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this skill?')) {
                        // Perform the delete action, e.g., sending an AJAX request to the server
                        $(`#skills_table tbody tr:has(button[data-id="${id}"])`).remove();
                    }
                });
            });
        </script>

    </div>

