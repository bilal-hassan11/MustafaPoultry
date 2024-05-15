<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta charset="utf-8" />
    <title>@yield('title') - {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin_assets') }}/images/favicon.png">

    <!-- App css -->
    <link href="{{ asset('admin_assets') }}/css/bundled.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets') }}/css/dianujAdminCss.css" rel="stylesheet" type="text/css" />
    <link href="//fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

</head>

<body class="authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-5">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                {{-- <a href="{{route('admin.home')}}">
                                    <span><img src="{{ asset('admin_assets') }}/images/web_logo_dark.png" alt="" width="200"></span>
                                </a> --}}
                                <p class="text-muted mb-4 mt-3">@yield('page-heading')</p>
                            </div>
                            @yield('content')
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    @yield("after-page")
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <footer class="footer footer-alt">
        {{ date('Y') }} &copy; All rights reserved by {{config('app.name')}}.
    </footer>

    <script src="{{ asset('admin_assets') }}/js/bundled.min.js"></script>
    <script src="{{ asset('admin_assets') }}/js/custom.js"></script>

    @yield('page-scripts')

</body>

</html>