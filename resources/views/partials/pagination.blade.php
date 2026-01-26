@php
    $routeName = $route ?? Route::currentRouteName();
@endphp
<form action="{{ route($routeName) }}" method="get">
    @foreach(request()->except(['perPage', 'page']) as $key => $value)
        @if(is_array($value))
            @foreach($value as $v)
                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-inline-flex align-items-center">
            <label for="perPage" class="form-label mb-0 mr-2">{{ __('Items per page') }}</label>
            <select name="perPage" id="perPage" class="form-control form-control-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('perPage', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage', 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage', 10) == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <div>
            {!! $paginator->withQueryString()->links() !!}
        </div>
    </div>
</form>
