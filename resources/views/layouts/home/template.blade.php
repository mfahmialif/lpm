<!doctype html>
<html lang="en" class="no-js">

<head>
    @include('layouts.home.header')
</head>

<body>
    @include('layouts.home.navbar')

    @include('layouts.home.sidebar')

    <!-- Main -->
    <main>
       @yield('content')
    </main>

    @include('layouts.home.footer')

    <!-- Modal and Drawer Overlay -->
    <drawer-opener id="drawer-overlay"></drawer-opener>

    <!-- Scroll to Top Button -->
    <scroll-top>
        <div class="scroll-to-top">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
            </svg>
        </div>
    </scroll-top>

    @include('layouts.home.script')
</body>

</html>
