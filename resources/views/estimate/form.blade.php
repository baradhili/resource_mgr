<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $estimate?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="scope" class="form-label">{{ __('Scope') }}</label>
            <input type="text" name="scope" class="form-control @error('scope') is-invalid @enderror" value="{{ old('scope', $estimate?->scope) }}" id="scope" placeholder="Scope of Work">
            {!! $errors->first('uscope', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <!-- <div class="form-group mb-2 mb20">
            <label for="use_name_as_title" class="form-label">{{ __('Use Name As Title') }}</label>
            <input type="text" name="use_name_as_title" class="form-control @error('use_name_as_title') is-invalid @enderror" value="{{ old('use_name_as_title', $estimate?->use_name_as_title) }}" id="use_name_as_title" placeholder="Use Name As Title">
            {!! $errors->first('use_name_as_title', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> -->
        <div class="form-group mb-2 mb20">
            <label for="expiration_date" class="form-label">{{ __('Expiration Date') }}</label>
            <input type="text" name="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror" value="{{ old('expiration_date', $estimate?->expiration_date) }}" id="expiration_date" placeholder="Expiration Date">
            {!! $errors->first('expiration_date', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <!-- <div class="form-group mb-2 mb20">
            <label for="currency_symbol" class="form-label">{{ __('Currency Symbol') }}</label>
            <input type="text" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror" value="{{ old('currency_symbol', $estimate?->currency_symbol) }}" id="currency_symbol" placeholder="Currency Symbol">
            {!! $errors->first('currency_symbol', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="currency_decimal_separator" class="form-label">{{ __('Currency Decimal Separator') }}</label>
            <input type="text" name="currency_decimal_separator" class="form-control @error('currency_decimal_separator') is-invalid @enderror" value="{{ old('currency_decimal_separator', $estimate?->currency_decimal_separator) }}" id="currency_decimal_separator" placeholder="Currency Decimal Separator">
            {!! $errors->first('currency_decimal_separator', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="currency_thousands_separator" class="form-label">{{ __('Currency Thousands Separator') }}</label>
            <input type="text" name="currency_thousands_separator" class="form-control @error('currency_thousands_separator') is-invalid @enderror" value="{{ old('currency_thousands_separator', $estimate?->currency_thousands_separator) }}" id="currency_thousands_separator" placeholder="Currency Thousands Separator">
            {!! $errors->first('currency_thousands_separator', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="allows_to_select_items" class="form-label">{{ __('Allows To Select Items') }}</label>
            <input type="text" name="allows_to_select_items" class="form-control @error('allows_to_select_items') is-invalid @enderror" value="{{ old('allows_to_select_items', $estimate?->allows_to_select_items) }}" id="allows_to_select_items" placeholder="Allows To Select Items">
            {!! $errors->first('allows_to_select_items', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> -->
        <div class="form-group mb-2 mb20">
            <label for="tags" class="form-label">{{ __('Tags') }}</label>
            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" value="{{ old('tags', $estimate?->tags) }}" id="tags" placeholder="Tags">
            {!! $errors->first('tags', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estimate_owner" class="form-label">{{ __('Estimate Owner') }}</label>
            <input type="text" name="estimate_owner" class="form-control @error('estimate_owner') is-invalid @enderror" value="{{ old('estimate_owner', $estimate?->estimate_owner) }}" id="estimate_owner" placeholder="Estimate Owner">
            {!! $errors->first('estimate_owner', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="partner" class="form-label">{{ __('Partner') }}</label>
            <input type="text" name="partner" class="form-control @error('partner') is-invalid @enderror" value="{{ old('partner', $estimate?->partner) }}" id="partner" placeholder="Partner">
            {!! $errors->first('partner', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <ul>
        <li>Items </li>
        </ul>
        <div class="form-group mb-2 mb20">
            <label for="total_cost" class="form-label">{{ __('Total Cost') }}</label>
            <input type="text" name="total_cost" class="form-control @error('total_cost') is-invalid @enderror" value="{{ old('total_cost', $estimate?->total_cost) }}" id="total_cost" placeholder="Total Cost">
            {!! $errors->first('total_cost', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
               <ul>
 
    <li>Risks </li>
    <li>Assumptions </li>
    <li>Terms and Conditions    </li>
        </ul>
        <!-- <div class="form-group mb-2 mb20">
            <label for="created_by" class="form-label">{{ __('Created By') }}</label>
            <input type="text" name="created_by" class="form-control @error('created_by') is-invalid @enderror" value="{{ old('created_by', $estimate?->created_by) }}" id="created_by" placeholder="Created By">
            {!! $errors->first('created_by', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="updated_by" class="form-label">{{ __('Updated By') }}</label>
            <input type="text" name="updated_by" class="form-control @error('updated_by') is-invalid @enderror" value="{{ old('updated_by', $estimate?->updated_by) }}" id="updated_by" placeholder="Updated By">
            {!! $errors->first('updated_by', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> -->

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>