<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/favicon-admin.png') }}" alt="logo">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>

        <!-- News-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="News">News</span>
        </li>

        <!-- Category -->
        <li class="menu-item {{ request()->routeIs('admin.category*') ? 'active' : '' }}">
            <a href="{{ route('admin.category.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Category">Category</div>
            </a>
        </li>
        <!-- News -->
        <li class="menu-item {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
            <a href="{{ route('admin.news.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="News">News</div>
            </a>
        </li>

        <!-- Game-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Game">Game</span>
        </li>

        <!-- Player -->
        <li class="menu-item {{ request()->routeIs('admin.player*') ? 'active' : '' }}">
            <a href="{{ route('admin.player.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Player">Player</div>
            </a>
        </li>
        <!-- Score -->
        <li class="menu-item">
            <a href="app-email.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Score">Score</div>
            </a>
        </li>

        <!-- Administrator-->
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Administrator">Administrator</span>
        </li>

        <!-- Users -->
        <li class="menu-item">
            <a href="app-email.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Users">Users</div>
            </a>
        </li>
        <!-- Profile -->
        <li class="menu-item">
            <a href="app-email.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Profile">Profile</div>
            </a>
        </li>
        <!-- Logout -->
        <li class="menu-item">
            <a href="app-email.html" class="menu-link">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Logout">Logout</div>
            </a>
        </li>

    </ul>
</aside>
