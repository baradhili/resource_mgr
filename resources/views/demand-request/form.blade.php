<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="project_id" class="form-label">{{ __('Project Id') }}</label>
            <input type="text" name="project_id" class="form-control @error('project_id') is-invalid @enderror" value="{{ old('project_id', $demandRequest?->project_id) }}" id="project_id" placeholder="Project Id">
            {!! $errors->first('project_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="requester_id" class="form-label">{{ __('Requester Id') }}</label>
            <input type="text" name="requester_id" class="form-control @error('requester_id') is-invalid @enderror" value="{{ old('requester_id', $demandRequest?->requester_id) }}" id="requester_id" placeholder="Requester Id">
            {!! $errors->first('requester_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="forecast_demand_id" class="form-label">{{ __('Forecast Demand Id') }}</label>
            <input type="text" name="forecast_demand_id" class="form-control @error('forecast_demand_id') is-invalid @enderror" value="{{ old('forecast_demand_id', $demandRequest?->forecast_demand_id) }}" id="forecast_demand_id" placeholder="Forecast Demand Id">
            {!! $errors->first('forecast_demand_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="allocated_resource_id" class="form-label">{{ __('Allocated Resource Id') }}</label>
            <input type="text" name="allocated_resource_id" class="form-control @error('allocated_resource_id') is-invalid @enderror" value="{{ old('allocated_resource_id', $demandRequest?->allocated_resource_id) }}" id="allocated_resource_id" placeholder="Allocated Resource Id">
            {!! $errors->first('allocated_resource_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="funded_estimate_id" class="form-label">{{ __('Funded Estimate Id') }}</label>
            <input type="text" name="funded_estimate_id" class="form-control @error('funded_estimate_id') is-invalid @enderror" value="{{ old('funded_estimate_id', $demandRequest?->funded_estimate_id) }}" id="funded_estimate_id" placeholder="Funded Estimate Id">
            {!! $errors->first('funded_estimate_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="funded_by_id" class="form-label">{{ __('Funded By Id') }}</label>
            <input type="text" name="funded_by_id" class="form-control @error('funded_by_id') is-invalid @enderror" value="{{ old('funded_by_id', $demandRequest?->funded_by_id) }}" id="funded_by_id" placeholder="Funded By Id">
            {!! $errors->first('funded_by_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="client_id" class="form-label">{{ __('Client Id') }}</label>
            <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $demandRequest?->client_id) }}" id="client_id" placeholder="Client Id">
            {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $demandRequest?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="priority" class="form-label">{{ __('Priority') }}</label>
            <input type="text" name="priority" class="form-control @error('priority') is-invalid @enderror" value="{{ old('priority', $demandRequest?->priority) }}" id="priority" placeholder="Priority">
            {!! $errors->first('priority', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="role_title" class="form-label">{{ __('Role Title') }}</label>
            <input type="text" name="role_title" class="form-control @error('role_title') is-invalid @enderror" value="{{ old('role_title', $demandRequest?->role_title) }}" id="role_title" placeholder="Role Title">
            {!! $errors->first('role_title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="quantity_fte" class="form-label">{{ __('Quantity Fte') }}</label>
            <input type="text" name="quantity_fte" class="form-control @error('quantity_fte') is-invalid @enderror" value="{{ old('quantity_fte', $demandRequest?->quantity_fte) }}" id="quantity_fte" placeholder="Quantity Fte">
            {!! $errors->first('quantity_fte', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
            <input type="text" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $demandRequest?->start_date) }}" id="start_date" placeholder="Start Date">
            {!! $errors->first('start_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="end_date" class="form-label">{{ __('End Date') }}</label>
            <input type="text" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $demandRequest?->end_date) }}" id="end_date" placeholder="End Date">
            {!! $errors->first('end_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="filled_at" class="form-label">{{ __('Filled At') }}</label>
            <input type="text" name="filled_at" class="form-control @error('filled_at') is-invalid @enderror" value="{{ old('filled_at', $demandRequest?->filled_at) }}" id="filled_at" placeholder="Filled At">
            {!! $errors->first('filled_at', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="is_funded" class="form-label">{{ __('Is Funded') }}</label>
            <input type="text" name="is_funded" class="form-control @error('is_funded') is-invalid @enderror" value="{{ old('is_funded', $demandRequest?->is_funded) }}" id="is_funded" placeholder="Is Funded">
            {!! $errors->first('is_funded', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="funding_source" class="form-label">{{ __('Funding Source') }}</label>
            <input type="text" name="funding_source" class="form-control @error('funding_source') is-invalid @enderror" value="{{ old('funding_source', $demandRequest?->funding_source) }}" id="funding_source" placeholder="Funding Source">
            {!! $errors->first('funding_source', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="budget_code" class="form-label">{{ __('Budget Code') }}</label>
            <input type="text" name="budget_code" class="form-control @error('budget_code') is-invalid @enderror" value="{{ old('budget_code', $demandRequest?->budget_code) }}" id="budget_code" placeholder="Budget Code">
            {!! $errors->first('budget_code', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="approved_budget_amount" class="form-label">{{ __('Approved Budget Amount') }}</label>
            <input type="text" name="approved_budget_amount" class="form-control @error('approved_budget_amount') is-invalid @enderror" value="{{ old('approved_budget_amount', $demandRequest?->approved_budget_amount) }}" id="approved_budget_amount" placeholder="Approved Budget Amount">
            {!! $errors->first('approved_budget_amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>