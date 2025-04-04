<div>
    <a href="/contracts/" class="text-decoration-none text-dark">
        <div class="row">
            <div class="col mt-0">
                <h5 class="card-title">Contractor Tenure</h5>
            </div>

            <div class="col-auto">
                <div class="stat text-primary">
                    <x-feathericon-users class="align-middle" />
                </div>
            </div>
        </div>
        <h1 class="mt-1 mb-3 d-inline-block">{{ $resourcesEndingSoonCount }}</h1>
        <span class="d-inline-block"> ending in the next 3 months</span>
        <div class="mb-0">
            <span class="text-muted">Next: {{ $nextPersonOut }}</span>
        </div>
    </a>
</div>
