<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Dashboard' }} - {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin_assets') }}/images/favicon.png">

    <!-- Google Font css -->
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
    <link href="//fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="//fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <!-- select 2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script></script>

    <!-- App css -->
    <link href="{{ asset('admin_assets') }}/css/bundled.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets') }}/css/dianujAdminCss.css" rel="stylesheet" type="text/css" />
</head>

<body data-layout-mode="default" data-theme="light" data-layout-width="fluid" data-topbar-color="dark" data-menu-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='false'>


    <div id="preloader" class="preloader">
        <div id="status">
            <div class="spinner">Loading...</div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <div class="navbar-custom" style="background-color: #4a81d4">
            <div class="container-fluid">
                @include('layouts.admin.topbar')
            </div>
        </div>
        <!-- end Topbar -->

        <!-- ======= Left Sidebar Start ======= -->
        @include('layouts.admin.navbar')
        <!-- Left Sidebar End -->

        <!-- ========================================== -->
        <!-- Start Page Content here -->
        <!-- ========================================== -->
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            {{ date('Y') }} &copy; All rights reserved by {{config('app.name')}}
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>

        <!-- ========================================== -->
        <!-- End Page content -->
        <!-- ========================================== -->


    </div>
    <!-- END wrapper -->
    <script src="{{ asset('admin_assets') }}/src/js/pages/data-table.js"></script>
	
    <script src="{{ asset('admin_assets') }}/js/bundled.min.js"></script>
    <script src="{{ asset('admin_assets') }}/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
        $('.js-example-basic-single ').select2();
        });        
    </script>    
    @yield('page-scripts')
</body>

</html>
