<div >
    <div class="row">
        <div class="col mt-0">
            <h5 class="card-title">Average Workload</h5>
        </div>

        <div class="col-auto">
            <div class="stat text-primary">
                <x-feathericon-activity class="align-middle" />
            </div>
        </div>
    </div>
    <h1 class="mt-1 mb-3">{{$currentMonthAverage}}%</h1>
    <div class="mb-0">
        <span class="text-danger">{{$delta}}</span>
        <span class="text-muted">Since last month</span>
    </div>
</div>
