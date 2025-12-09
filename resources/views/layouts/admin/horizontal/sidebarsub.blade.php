<ul class="menu-sub">
    @foreach ($menu as $submenu)
    @php
    $activeClass = null;
    $currentRouteName = Route::currentRouteName();

    if (isset($submenu->submenu)) {
    if (gettype($submenu->slug) === 'array') {
    foreach ($submenu->slug as $slug) {
    if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
    $activeClass = 'active open';
    }
    }
    } else {
    if (
    str_contains($currentRouteName, $submenu->slug) and
    strpos($currentRouteName, $submenu->slug) === 0
    ) {
    $activeClass = 'active open';
    }
    }
    } elseif (strpos($currentRouteName, $submenu->slug) === 0) {
    $activeClass = 'active';
    }
    @endphp

    <li class="menu-item {{ $activeClass }}">
        <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0);' }}" class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($submenu->target) and !empty($submenu->target)) target="_blank" @endif>
            @isset($submenu->icon)
            <i class="{{ $submenu->icon }}"></i>
            @endisset
            <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
            @isset($submenu->badge)
            <div class="badge bg-{{ $submenu->badge[0] }} rounded-pill ms-auto">{{ $submenu->badge[1] }}</div>
            @endisset
        </a>

        {{-- nested submenu --}}
        @isset($submenu->submenu)
        @include('layouts.admin.horizontal.sidebarsub', ['menu' => $submenu->submenu])
        @endisset
    </li>
    @endforeach
</ul>
