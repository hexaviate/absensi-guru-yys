<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.head')
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layout.nav')
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    @include('layout.sidebar')
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @yield('main')
            </div>
            <footer class="main-footer">
                @include('layout.footer')
            </footer>
        </div>
    </div>

    @include('layout.script')

</body>

</html>
