<div class="main">
    @include('layouts.topbar')

    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3">@yield('template_title')</h1>
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row text-muted">
                <div class="col-6 text-start">
                    <p class="mb-0">
                        <strong>Resource Manager</strong> © 2024
                    </p>
                </div>
                <div class="col-6 text-end">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a class="text-muted" href="https://adminkit.io/" target="_blank">_</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
