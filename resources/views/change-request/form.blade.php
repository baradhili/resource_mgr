<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="record_type" class="form-label">{{ __('Record Type') }}</label>
            <input type="text" name="record_type" class="form-control @error('record_type') is-invalid @enderror" value="{{ old('record_type', $changeRequest?->record_type) }}" id="record_type" placeholder="Record Type">
            {!! $errors->first('record_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="record_id" class="form-label">{{ __('Record Id') }}</label>
            <input type="text" name="record_id" class="form-control @error('record_id') is-invalid @enderror" value="{{ old('record_id', $changeRequest?->record_id) }}" id="record_id" placeholder="Record Id">
            {!! $errors->first('record_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="field" class="form-label">{{ __('Field') }}</label>
            <input type="text" name="field" class="form-control @error('field') is-invalid @enderror" value="{{ old('field', $changeRequest?->field) }}" id="field" placeholder="Field">
            {!! $errors->first('field', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="old_value" class="form-label">{{ __('Old Value') }}</label>
            <input type="text" name="old_value" class="form-control @error('old_value') is-invalid @enderror" value="{{ old('old_value', $changeRequest?->old_value) }}" id="old_value" placeholder="Old Value">
            {!! $errors->first('old_value', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="new_value" class="form-label">{{ __('New Value') }}</label>
            <input type="text" name="new_value" class="form-control @error('new_value') is-invalid @enderror" value="{{ old('new_value', $changeRequest?->new_value) }}" id="new_value" placeholder="New Value">
            {!! $errors->first('new_value', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $changeRequest?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notes') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $changeRequest?->notes) }}" id="notes" placeholder="Notes">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="requested_by" class="form-label">{{ __('Requested By') }}</label>
            <input type="text" name="requested_by" class="form-control @error('requested_by') is-invalid @enderror" value="{{ old('requested_by', $changeRequest?->requested_by) }}" id="requested_by" placeholder="Requested By">
            {!! $errors->first('requested_by', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="approved_by" class="form-label">{{ __('Approved By') }}</label>
            <input type="text" name="approved_by" class="form-control @error('approved_by') is-invalid @enderror" value="{{ old('approved_by', $changeRequest?->approved_by) }}" id="approved_by" placeholder="Approved By">
            {!! $errors->first('approved_by', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="approval_date" class="form-label">{{ __('Approval Date') }}</label>
            <input type="text" name="approval_date" class="form-control @error('approval_date') is-invalid @enderror" value="{{ old('approval_date', $changeRequest?->approval_date) }}" id="approval_date" placeholder="Approval Date">
            {!! $errors->first('approval_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>