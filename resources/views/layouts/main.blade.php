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
                        <p class="mb-0" style="color: inherit;">
                        <strong><a href="https://github.com/baradhili/resource_mgr" target="_blank" style="color: inherit;">Resource Manager</a></strong> 
                    </p>
                </div>
            </div>
        </div>
    </footer>
</div>
