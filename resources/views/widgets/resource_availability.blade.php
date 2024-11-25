<div>
    <div class="row">
        <div class="col mt-0">
            <h5 class="card-title">Resource Availability</h5>
        </div>

        <div class="col-auto">
            <div class="stat text-primary">
                <i class="align-middle" data-feather="battery"></i>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($yearMonthSums as $month => $sum)
            <div class="col">
                <div style="margin-bottom: 0; padding-bottom: 0" class="row justify-content-center align-items-end">
                    <div class="col text-center">
                        <h1 style="margin-bottom: 0; padding-bottom: 0">{{ number_format(round($sum, 1), 1) }}</h1>
                    </div>
                </div>
                <div class="row justify-content-center align-items-start">
                    <div class="col text-center">
                        {{ $month }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mb-0">
        <span class="text-danger">{{ number_format((($yearMonthSums[array_key_last($yearMonthSums)] / $yearMonthSums[array_key_first($yearMonthSums)] - 1) * 100), 2) }}%</span>
        <span class="text-muted">next month</span>
    </div>
</div>
