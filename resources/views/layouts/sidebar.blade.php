<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content"
                        style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <a class="sidebar-brand" href="/">
                                <span class="align-middle white">Resource Manager</span>
                            </a>
                            <ul class="sidebar-nav">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('home') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                        </svg> <span class="align-middle">Home</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a data-bs-target="#resources" data-bs-toggle="collapse" class="sidebar-link"
                                        aria-expanded="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-users">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg> <span class="align-middle">Resources</span>
                                    </a>
                                    <ul id="resources" class="sidebar-dropdown list-unstyled collapse show">
                                        <li class="sidebar-item"><a class="sidebar-link"
                                                href="{{ route('resources.index') }}">Resources</a></li>
                                        @can('contracts.index')<li class="sidebar-item"><a class="sidebar-link"
                                                href="{{ route('contracts.index') }}">Contracts </a></li>@endcan
                                        @can('leaves.index')<li class="sidebar-item"><a class="sidebar-link"
                                                href="{{ route('leaves.index') }}">Leave </a></li>@endcan
                                    </ul>
                                </li>

                                <li class="sidebar-item">
                                    <a data-bs-target="#skills-services" data-bs-toggle="collapse" class="sidebar-link"
                                        aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-key">
                                            <path
                                                d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                            </path>
                                        </svg><span class="align-middle">Skills and Services</span>
                                    </a>
                                    <ul id="skills-services" class="sidebar-dropdown list-unstyled collapse">
                                        @can('skills.index')<li class="sidebar-item"><a class="sidebar-link"
                                                href="{{ route('skills.index') }}">Skills</a></li>@endcan
                                        @can('services.index')<li class="sidebar-item"><a class="sidebar-link"
                                                href="{{ route('services.index') }}">Services </a></li>@endcan
                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('projects.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-list align-middle">
                                            <line x1="8" y1="6" x2="21" y2="6">
                                            </line>
                                            <line x1="8" y1="12" x2="21" y2="12">
                                            </line>
                                            <line x1="8" y1="18" x2="21" y2="18">
                                            </line>
                                            <line x1="3" y1="6" x2="3.01" y2="6">
                                            </line>
                                            <line x1="3" y1="12" x2="3.01" y2="12">
                                            </line>
                                            <line x1="3" y1="18" x2="3.01" y2="18">
                                            </line>
                                        </svg> <span class="align-middle">Projects</span>
                                    </a>
                                </li>
                                @can('allocations.index')<li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('allocations.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-calendar align-middle">
                                            <rect x="3" y="4" width="18" height="18" rx="2"
                                                ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6">
                                            </line>
                                            <line x1="8" y1="2" x2="8" y2="6">
                                            </line>
                                            <line x1="3" y1="10" x2="21" y2="10">
                                            </line>
                                        </svg> <span class="align-middle">Allocations</span>
                                    </a>
                                </li>@endcan
                                @can('demands.index')<li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('demands.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-git-pull-request">
                                            <circle cx="18" cy="18" r="3"></circle>
                                            <circle cx="6" cy="6" r="3"></circle>
                                            <path d="M13 6h3a2 2 0 0 1 2 2v7"></path>
                                            <line x1="6" y1="9" x2="6" y2="21">
                                            </line>
                                        </svg> <span class="align-middle">Demands</span>
                                    </a>
                                </li>
                                @endcan
                                @can('estimates.index')<li class="sidebar-item">
                                    <a data-bs-target="#estimates" data-bs-toggle="collapse" class="sidebar-link"
                                        aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-key">
                                            <path
                                                d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                            </path>
                                        </svg> <span class="align-middle">Estimates</span>
                                    </a>
                                    <ul id="estimates" class="sidebar-dropdown list-unstyled collapse">
                                        <li class="sidebar-item"><a class="sidebar-link"
                                                href="{{ route('clients.index') }}">Clients</a></li>
                                        <li class="sidebar-item"><a class="sidebar-link"
                                                href="{{ route('estimates.index') }}">Estimates </a></li>
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 952px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar"
                style="height: 381px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
        </div>
    </div>

</nav>
