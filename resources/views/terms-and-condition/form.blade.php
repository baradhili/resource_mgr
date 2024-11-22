<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="payment_terms" class="form-label">{{ __('Payment Terms') }}</label>
            <input type="text" name="payment_terms" class="form-control @error('payment_terms') is-invalid @enderror" value="{{ old('payment_terms', $termsAndCondition?->payment_terms) }}" id="payment_terms" placeholder="Payment Terms">
            {!! $errors->first('payment_terms', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="validity" class="form-label">{{ __('Validity') }}</label>
            <input type="text" name="validity" class="form-control @error('validity') is-invalid @enderror" value="{{ old('validity', $termsAndCondition?->validity) }}" id="validity" placeholder="Validity">
            {!! $errors->first('validity', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="assumptions" class="form-label">{{ __('Assumptions') }}</label>
            <input type="text" name="assumptions" class="form-control @error('assumptions') is-invalid @enderror" value="{{ old('assumptions', $termsAndCondition?->assumptions) }}" id="assumptions" placeholder="Assumptions">
            {!! $errors->first('assumptions', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="change_management" class="form-label">{{ __('Change Management') }}</label>
            <input type="text" name="change_management" class="form-control @error('change_management') is-invalid @enderror" value="{{ old('change_management', $termsAndCondition?->change_management) }}" id="change_management" placeholder="Change Management">
            {!! $errors->first('change_management', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>