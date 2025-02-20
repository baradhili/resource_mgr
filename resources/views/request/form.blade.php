<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="demand_type_id" class="form-label">{{ __('Demand Type Id') }}</label>
            <input type="text" name="demand_type_id" class="form-control @error('demand_type_id') is-invalid @enderror" value="{{ old('demand_type_id', $request?->demand_type_id) }}" id="demand_type_id" placeholder="Demand Type Id">
            {!! $errors->first('demand_type_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="product_group_function_domain_id" class="form-label">{{ __('Product Group Function Domain Id') }}</label>
            <input type="text" name="product_group_function_domain_id" class="form-control @error('product_group_function_domain_id') is-invalid @enderror" value="{{ old('product_group_function_domain_id', $request?->product_group_function_domain_id) }}" id="product_group_function_domain_id" placeholder="Product Group Function Domain Id">
            {!! $errors->first('product_group_function_domain_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="site_id" class="form-label">{{ __('Site Id') }}</label>
            <input type="text" name="site_id" class="form-control @error('site_id') is-invalid @enderror" value="{{ old('site_id', $request?->site_id) }}" id="site_id" placeholder="Site Id">
            {!! $errors->first('site_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="business_partner" class="form-label">{{ __('Business Partner') }}</label>
            <input type="text" name="business_partner" class="form-control @error('business_partner') is-invalid @enderror" value="{{ old('business_partner', $request?->business_partner) }}" id="business_partner" placeholder="Business Partner">
            {!! $errors->first('business_partner', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="request_title" class="form-label">{{ __('Request Title') }}</label>
            <input type="text" name="request_title" class="form-control @error('request_title') is-invalid @enderror" value="{{ old('request_title', $request?->request_title) }}" id="request_title" placeholder="Request Title">
            {!! $errors->first('request_title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="background" class="form-label">{{ __('Background') }}</label>
            <input type="text" name="background" class="form-control @error('background') is-invalid @enderror" value="{{ old('background', $request?->background) }}" id="background" placeholder="Background">
            {!! $errors->first('background', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="business_need" class="form-label">{{ __('Business Need') }}</label>
            <input type="text" name="business_need" class="form-control @error('business_need') is-invalid @enderror" value="{{ old('business_need', $request?->business_need) }}" id="business_need" placeholder="Business Need">
            {!! $errors->first('business_need', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="problem_statement" class="form-label">{{ __('Problem Statement') }}</label>
            <input type="text" name="problem_statement" class="form-control @error('problem_statement') is-invalid @enderror" value="{{ old('problem_statement', $request?->problem_statement) }}" id="problem_statement" placeholder="Problem Statement">
            {!! $errors->first('problem_statement', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="specific_requirements" class="form-label">{{ __('Specific Requirements') }}</label>
            <input type="text" name="specific_requirements" class="form-control @error('specific_requirements') is-invalid @enderror" value="{{ old('specific_requirements', $request?->specific_requirements) }}" id="specific_requirements" placeholder="Specific Requirements">
            {!! $errors->first('specific_requirements', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="funding_approval_stage_id" class="form-label">{{ __('Funding Approval Stage Id') }}</label>
            <input type="text" name="funding_approval_stage_id" class="form-control @error('funding_approval_stage_id') is-invalid @enderror" value="{{ old('funding_approval_stage_id', $request?->funding_approval_stage_id) }}" id="funding_approval_stage_id" placeholder="Funding Approval Stage Id">
            {!! $errors->first('funding_approval_stage_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="wbs_number" class="form-label">{{ __('Wbs Number') }}</label>
            <input type="text" name="wbs_number" class="form-control @error('wbs_number') is-invalid @enderror" value="{{ old('wbs_number', $request?->wbs_number) }}" id="wbs_number" placeholder="Wbs Number">
            {!! $errors->first('wbs_number', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="expected_start" class="form-label">{{ __('Expected Start') }}</label>
            <input type="text" name="expected_start" class="form-control @error('expected_start') is-invalid @enderror" value="{{ old('expected_start', $request?->expected_start) }}" id="expected_start" placeholder="Expected Start">
            {!! $errors->first('expected_start', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="expected_duration" class="form-label">{{ __('Expected Duration') }}</label>
            <input type="text" name="expected_duration" class="form-control @error('expected_duration') is-invalid @enderror" value="{{ old('expected_duration', $request?->expected_duration) }}" id="expected_duration" placeholder="Expected Duration">
            {!! $errors->first('expected_duration', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="business_value" class="form-label">{{ __('Business Value') }}</label>
            <input type="text" name="business_value" class="form-control @error('business_value') is-invalid @enderror" value="{{ old('business_value', $request?->business_value) }}" id="business_value" placeholder="Business Value">
            {!! $errors->first('business_value', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="business_unit" class="form-label">{{ __('Business Unit') }}</label>
            <input type="text" name="business_unit" class="form-control @error('business_unit') is-invalid @enderror" value="{{ old('business_unit', $request?->business_unit) }}" id="business_unit" placeholder="Business Unit">
            {!! $errors->first('business_unit', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="additional_expert_contact" class="form-label">{{ __('Additional Expert Contact') }}</label>
            <input type="text" name="additional_expert_contact" class="form-control @error('additional_expert_contact') is-invalid @enderror" value="{{ old('additional_expert_contact', $request?->additional_expert_contact) }}" id="additional_expert_contact" placeholder="Additional Expert Contact">
            {!! $errors->first('additional_expert_contact', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="attachments" class="form-label">{{ __('Attachments') }}</label>
            <input type="text" name="attachments" class="form-control @error('attachments') is-invalid @enderror" value="{{ old('attachments', $request?->attachments) }}" id="attachments" placeholder="Attachments">
            {!! $errors->first('attachments', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resource_type" class="form-label">{{ __('Resource Type') }}</label>
            <input type="text" name="resource_type" class="form-control @error('resource_type') is-invalid @enderror" value="{{ old('resource_type', $request?->resource_type) }}" id="resource_type" placeholder="Resource Type">
            {!! $errors->first('resource_type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fte" class="form-label">{{ __('Fte') }}</label>
            <input type="text" name="fte" class="form-control @error('fte') is-invalid @enderror" value="{{ old('fte', $request?->fte) }}" id="fte" placeholder="Fte">
            {!! $errors->first('fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $request?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>