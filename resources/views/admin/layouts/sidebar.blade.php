<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="{{ url('/dashboard') }}" class="app-brand-link justify-content-center w-100">
            <span class="app-brand-logo demo">
                {{-- <span class="text-primary">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                            fill="currentColor" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                            fill="currentColor" />
                    </svg>
                </span> --}}
                <img src="{{ asset('assets/logo.png') }}" alt="" class="img-fluid" width="150">
            </span>
            {{-- <span class="app-brand-text demo menu-text fw-bold ms-3">Vogue Portal</span> --}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        @can('dashboard')
            <li class="menu-item {{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                <a href="{{ url('/dashboard') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-home"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>
        @endcan

        <!-- Quotes Management -->
        @canany(['view-quotes', 'create-quotes', 'edit-quotes', 'delete-quotes', 'view-web-inquiries', 'view-accepted-quotes', 'view-archived-quotes', 'view-updated-quotes', 'proceed-to-accepted-quote', 'proceed-to-archived-quote', 'proceed-to-job-card', 'proceed-to-sale', 'proceed-to-invoice', 'mark-as-paid', 'vrm-tracking'])
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Quotes Management">Quotes Management</span>
            </li>

            <li class="menu-item {{ request()->segment(2) == 'vrm-tracker' || request()->segment(3) == 'create' || request()->segment(2) == 'web-inquiries' || request()->segment(2) == 'updated-quotes' || request()->segment(2) == 'accepted-quotes' ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-smart-home"></i>
                    <div data-i18n="Quotes">Quotes</div>
                </a>
                <ul class="menu-sub">
                    @can('vrm-tracking')
                        <li class="menu-item {{ request()->segment(2) == 'vrm-tracker' ? 'active' : '' }}">
                            <a href="#" class="menu-link">
                                <div data-i18n="VRM Tracker">Vrm Tracker</div>
                            </a>
                        </li>
                    @endcan
                    @can('create-quotes')
                        <li class="menu-item {{ request()->segment(2) == 'quote' && request()->segment(3) == 'create' ? 'active' : '' }}">
                            <a href="{{ route('quotes.quote.create') }}" class="menu-link">
                                <div data-i18n="New Quote">New Quote</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-web-inquiries')
                        <li class="menu-item {{ request()->segment(2) == 'web-inquiries' ? 'active' : '' }}">
                            <a href="{{ route('quotes.web-inquiries') }}" class="menu-link">
                                <div data-i18n="Web Inquiries">Web Inquiries</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-updated-quotes')
                        <li class="menu-item {{ request()->segment(2) == 'updated-quotes' ? 'active' : '' }}">
                            <a href="{{ route('quotes.updated-quotes') }}" class="menu-link">
                                <div data-i18n="Updated Quotes">Updated Quotes</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-accepted-quotes')
                        <li class="menu-item {{ request()->segment(2) == 'accepted-quotes' ? 'active' : '' }}">
                            <a href="{{ route('quotes.accepted-quotes') }}" class="menu-link">
                                <div data-i18n="Accepted Quotes">Accepted Quotes</div>
                            </a>
                        </li>
                    @endcan
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Reserve Parking">Reserve Parking</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Job Card">Job Card</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div data-i18n="Sales">Sales</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcanany

        <!-- Calendar -->
        @can('view-calendar')
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Calendar">Calendar</span>
            </li>
            <li class="menu-item {{ request()->segment(1) == 'calendar' ? 'active' : '' }}">
                <a href="{{ route('calendar.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-calendar"></i>
                    <div data-i18n="Calendar">Calendar</div>
                </a>
            </li>
        @endcan

        
        <!-- Website Management -->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Settings">Settings</span>
        </li>
        @can('view-websites')
            <li class="menu-item {{ request()->segment(1) == 'websites' ? 'active' : '' }}">
                <a href="{{route('websites.index')}}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-globe"></i>
                    <div data-i18n="Websites">Websites</div>
                </a>
            </li>
        @endcan

        <!-- Bank Management -->
        @can('view-banks')
            <li class="menu-item {{ request()->segment(1) == 'banks' ? 'active' : '' }}">
                <a href="{{route('banks.index')}}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-building-bank"></i>
                    <div data-i18n="Banks">Banks</div>
                </a>
            </li>
        @endcan
        
        <!-- Email Template Management -->
        @can('view-email-templates')
            <li class="menu-item {{ request()->segment(1) == 'email-templates' ? 'active' : '' }}">
                <a href="{{route('email-templates.index')}}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-template"></i>
                    <div data-i18n="Email Templates">Email Templates</div>
                </a>
            </li>
        @endcan

        <!-- Customer Type Management -->
        @can('view-customer-types')
            <li class="menu-item {{ request()->segment(1) == 'customer-types' ? 'active' : '' }}">
                <a href="{{route('customer-types.index')}}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-user"></i>
                    <div data-i18n="Customer Types">Customer Types</div>
                </a>
            </li>
        @endcan

        <!-- Part Management -->
        @can('view-parts')
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Parts Management">Parts Management</span>
            </li>
            <li class="menu-item {{ request()->segment(1) == 'parts' ? 'active' : '' }}">
                <a href="{{route('parts.index')}}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-cube"></i>
                    <div data-i18n="Parts">Parts</div>
                </a>
            </li>
        @endcan

        <!-- Ramp Management -->
        {{-- <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Ramp Management">Ramp Management</span>
        </li>

        <li class="menu-item {{ request()->segment(1) == 'ramp' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-adjustments"></i>
                <div data-i18n="Ramp Management">Ramp Management</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div data-i18n="Ramp Dashboard">Ramp Dashboard</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div data-i18n="Ramp Hall">Ramp Hall</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div data-i18n="Ramp List">Ramp List</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div data-i18n="Ramp Inquiries">Ramp Inquiries</div>
                    </a>
                </li>
            </ul>
        </li> --}}

        <!-- Archived Quotes -->
        @can('view-archived-quotes')
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Archived Quotes">Archived Quotes</span>
            </li>
            <li class="menu-item {{ request()->segment(2) == 'archived-quotes' ? 'active' : '' }}">
                <a href="{{ route('quotes.archived-quotes') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-archive"></i>
                    <div data-i18n="Archived Quotes">Archived Quotes</div>
                </a>
            </li>
        @endcan

        <!-- User Management -->
        @canany(['view-roles', 'view-users'])
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="User Management">User Management</span>
            </li>

            <li class="menu-item {{ request()->segment(1) == 'roles' || request()->segment(1) == 'users' ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-users"></i>
                    <div data-i18n="Users">Users</div>
                </a>
                <ul class="menu-sub">
                    @can('view-roles')
                        <li class="menu-item {{ request()->segment(1) == 'roles' ? 'active' : '' }}">
                            <a href="{{route('roles.index')}}" class="menu-link">
                                <div data-i18n="Roles">Roles</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-users')
                        <li class="menu-item {{ request()->segment(1) == 'users' ? 'active' : '' }}">
                            <a href="{{route('users.index')}}" class="menu-link">
                                <div data-i18n="Users">Users</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        
        <!-- Reports Management -->
        {{-- <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Reports">Reports</span>
        </li>

        <li class="menu-item {{ request()->segment(1) == 'reports' ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-file-text"></i>
                <div data-i18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div data-i18n="VRM Logs">VRM Logs</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div data-i18n="User Logs">User Logs</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div data-i18n="Sale Report">Sale Report</div>
                    </a>
                </li>
            </ul>
        </li> --}}

    </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
        <i class="ti tabler-menu icon-base"></i>
        <i class="ti tabler-chevron-right icon-base"></i>
    </a>
</div>
<!-- / Menu -->
