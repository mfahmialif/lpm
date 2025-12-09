<!doctype html>

@php
$sidebarLayout = $_COOKIE['sidebar_layout'] ?? 'vertical';
$templatePath = $sidebarLayout === 'horizontal' ? 'layouts.admin.horizontal.template' : 'layouts.admin.template';
@endphp

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('admin/assets') . '/' }}" data-template="{{ $sidebarLayout }}-menu-template" data-style="light">

<head>
    @if($sidebarLayout === 'horizontal')
    @include('layouts.admin.horizontal.header')
    @else
    @include('layouts.admin.header')
    @endif
    @stack('css')
</head>

<body>
    @if($sidebarLayout === 'horizontal')
    <!-- Horizontal Layout -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            @include('layouts.admin.horizontal.navbar')

            <div class="layout-page">
                <div class="content-wrapper">
                    @include('layouts.admin.horizontal.sidebar')

                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>

                    @include('layouts.admin.horizontal.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
    @else
    <!-- Vertical Layout -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.admin.sidebar')

            <div class="layout-page">
                @include('layouts.admin.navbar')

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>

                    @include('layouts.admin.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    @endif

    @if($sidebarLayout === 'horizontal')
    @include('layouts.admin.horizontal.scripts')
    @else
    @include('layouts.admin.scripts')
    @endif

    @stack('scripts')
</body>

</html>
