<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="opportunity_id" class="form-label">{{ __('Opportunity Id') }}</label>
            <input type="text" name="opportunity_id" class="form-control @error('opportunity_id') is-invalid @enderror" value="{{ old('opportunity_id', $forecastDemand?->opportunity_id) }}" id="opportunity_id" placeholder="Opportunity Id">
            {!! $errors->first('opportunity_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="owner_id" class="form-label">{{ __('Owner Id') }}</label>
            <input type="text" name="owner_id" class="form-control @error('owner_id') is-invalid @enderror" value="{{ old('owner_id', $forecastDemand?->owner_id) }}" id="owner_id" placeholder="Owner Id">
            {!! $errors->first('owner_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="stage" class="form-label">{{ __('Stage') }}</label>
            <input type="text" name="stage" class="form-control `@error`('stage') is-invalid `@enderror`" value="{{ old('stage', $forecastDemand?->stage?->value) }}" id="stage" placeholder="Stage">
            {!! $errors->first('stage', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control `@error`('status') is-invalid `@enderror`" value="{{ old('status', $forecastDemand?->status?->value) }}" id="status" placeholder="Status">            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="win_probability" class="form-label">{{ __('Win Probability') }}</label>
            <input type="text" name="win_probability" class="form-control @error('win_probability') is-invalid @enderror" value="{{ old('win_probability', $forecastDemand?->win_probability) }}" id="win_probability" placeholder="Win Probability">
            {!! $errors->first('win_probability', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="client_id" class="form-label">{{ __('Client Id') }}</label>
            <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" value="{{ old('client_id', $forecastDemand?->client_id) }}" id="client_id" placeholder="Client Id">
            {!! $errors->first('client_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_year" class="form-label">{{ __('Start Year') }}</label>
            <input type="text" name="start_year" class="form-control @error('start_year') is-invalid @enderror" value="{{ old('start_year', $forecastDemand?->start_year) }}" id="start_year" placeholder="Start Year">
            {!! $errors->first('start_year', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="start_quarter" class="form-label">{{ __('Start Quarter') }}</label>
            <input type="text" name="start_quarter" class="form-control @error('start_quarter') is-invalid @enderror" value="{{ old('start_quarter', $forecastDemand?->start_quarter) }}" id="start_quarter" placeholder="Start Quarter">
            {!! $errors->first('start_quarter', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="duration_months" class="form-label">{{ __('Duration Months') }}</label>
            <input type="text" name="duration_months" class="form-control @error('duration_months') is-invalid @enderror" value="{{ old('duration_months', $forecastDemand?->duration_months) }}" id="duration_months" placeholder="Duration Months">
            {!! $errors->first('duration_months', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estimated_budget" class="form-label">{{ __('Estimated Budget') }}</label>
            <input type="text" name="estimated_budget" class="form-control @error('estimated_budget') is-invalid @enderror" value="{{ old('estimated_budget', $forecastDemand?->estimated_budget) }}" id="estimated_budget" placeholder="Estimated Budget">
            {!! $errors->first('estimated_budget', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="notes" class="form-label">{{ __('Notes') }}</label>
            <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $forecastDemand?->notes) }}" id="notes" placeholder="Notes">
            {!! $errors->first('notes', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>