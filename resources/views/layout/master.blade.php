<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    @include('layout.panels.style')
</head>

<body class="sidebar-mini layout-navbar-fixed">
    <div class="wrapper">

        <!-- Navbar -->
            @include('layout.panels.header')
        <!-- /.navbar -->

        <!-- SideBar Start -->
            @include('layout.panels.sidebar')
        <!-- SideBar End -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                @yield('content')
            </div>
        </div>
        <!-- /.content-wrapper -->

        <!-- UserBar Start -->
            @include('layout.panels.userbar')
        <!-- UserBar End -->

        <!-- Main Footer -->
    
        <!-- Footer Start -->
            @include('layout.panels.footer')
        <!-- Footer End -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    @include('layout.panels.script')
</body>

</html>
