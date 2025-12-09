 <!-- Menu -->
 <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
     <div class="container-xxl d-flex h-100">
         <ul class="menu-inner pb-2 pb-xl-0">
             @foreach ($menuData[1]->menu as $menu)
             {{-- adding active and open class if child is active --}}
             {{-- menu headers --}}
             @if (isset($menu->menuHeader))
             <li class="menu-header small">
                 <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
             </li>
             @else
             {{-- active menu method --}}
             @php
             $activeClass = null;
             $currentRouteName = Route::currentRouteName();

             if (isset($menu->submenu)) {
             if (gettype($menu->slug) === 'array') {
             foreach ($menu->slug as $slug) {
             if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
             $activeClass = 'active open';
             }
             }
             } else {
             if (
             str_contains($currentRouteName, $menu->slug) and
             strpos($currentRouteName, $menu->slug) === 0
             ) {
             $activeClass = 'active open';
             }
             }
             } elseif (strpos($currentRouteName, $menu->slug) === 0) {
             $activeClass = 'active';
             }
             @endphp

             {{-- main menu --}}
             <li class="menu-item {{ $activeClass }}">
                 <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                     @isset($menu->icon)
                     <i class="{{ $menu->icon }}"></i>
                     @endisset
                     <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                     @isset($menu->badge)
                     <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
                     @endisset
                 </a>

                 {{-- submenu --}}
                 @isset($menu->submenu)
                 @include('layouts.admin.horizontal.sidebarsub', ['menu' => $menu->submenu])
                 @endisset
             </li>
             @endif
             @endforeach
         </ul>
     </div>
 </aside>
