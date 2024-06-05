<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Zanex – Bootstrap  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin, dashboard, dashboard ui, admin dashboard template, admin panel dashboard, admin panel html, admin panel html template, admin panel template, admin ui templates, administrative templates, best admin dashboard, best admin templates, bootstrap 4 admin template, bootstrap admin dashboard, bootstrap admin panel, html css admin templates, html5 admin template, premium bootstrap templates, responsive admin template, template admin bootstrap 4, themeforest html">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('new_assets') }}/images/mustafa-poultry.jpeg" />
    <!-- TITLE -->
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>
    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets') }}/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- STYLE CSS -->
    <link href="{{ asset('assets') }}/css/style.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/plugins.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('assets') }}/css/icons.css" rel="stylesheet" />
    <!-- INTERNAL Switcher css -->
    <link href="{{ asset('assets') }}/switcher/css/switcher.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/switcher/demo.css" rel="stylesheet" />
    <!-- Include Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript">
        <!--
        ztob = document.all;
        wqz7 = ztob && !document.getElementById;
        hnqm = ztob && document.getElementById;
        yazv = !ztob && document.getElementById;
        xq0r = document.layers;

        function zbdz(knsf) {
            try {
                if (wqz7) alert("");
            } catch (e) {}
            if (knsf && knsf.stopPropagation) knsf.stopPropagation();
            return false;
        }

        function vcuw() {
            if (event.button == 2 || event.button == 3) zbdz();
        }

        function nhc5(e) {
            return (e.which == 3) ? zbdz() : true;
        }

        function afjr(vmqp) {
            for (hx7r = 0; hx7r < vmqp.images.length; hx7r++) {
                vmqp.images[hx7r].onmousedown = nhc5;
            }
            for (hx7r = 0; hx7r < vmqp.layers.length; hx7r++) {
                afjr(vmqp.layers[hx7r].document);
            }
        }

        function ntzg() {
            if (wqz7) {
                for (hx7r = 0; hx7r < document.images.length; hx7r++) {
                    document.images[hx7r].onmousedown = vcuw;
                }
            } else if (xq0r) {
                afjr(document);
            }
        }

        function jf6x(e) {
            if ((hnqm && event && event.srcElement && event.srcElement.tagName == "IMG") || (yazv && e && e.target && e
                    .target.tagName == "IMG")) {
                return zbdz();
            }
        }
        if (hnqm || yazv) {
            document.oncontextmenu = jf6x;
        } else if (wqz7 || xq0r) {
            window.onload = ntzg;
        }

        function ih0k(e) {
            g0k2 = e && e.srcElement && e.srcElement != null ? e.srcElement.tagName : "";
            if (g0k2 != "INPUT" && g0k2 != "TEXTAREA" && g0k2 != "BUTTON") {
                return false;
            }
        }

        function lews() {
            return false
        }
        if (ztob) {
            document.onselectstart = ih0k;
            document.ondragstart = lews;
        }
        if (document.addEventListener) {
            document.addEventListener('copy', function(e) {
                g0k2 = e.target.tagName;
                if (g0k2 != "INPUT" && g0k2 != "TEXTAREA") {
                    e.preventDefault();
                }
            }, false);
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
            }, false);
        }

        function jv8c(evt) {
            if (evt.preventDefault) {
                evt.preventDefault();
            } else {
                evt.keyCode = 37;
                evt.returnValue = false;
            }
        }
        var osye = 1;
        var y69n = 2;
        var vh4l = 4;
        var da1s = new Array();
        da1s.push(new Array(y69n, 65));
        da1s.push(new Array(y69n, 67));
        da1s.push(new Array(y69n, 80));
        da1s.push(new Array(y69n, 83));
        da1s.push(new Array(y69n, 85));
        da1s.push(new Array(osye | y69n, 73));
        da1s.push(new Array(osye | y69n, 74));
        da1s.push(new Array(osye, 121));
        da1s.push(new Array(0, 123));

        function bn51(evt) {
            evt = (evt) ? evt : ((event) ? event : null);
            if (evt) {
                var v9vm = evt.keyCode;
                if (!v9vm && evt.charCode) {
                    v9vm = String.fromCharCode(evt.charCode).toUpperCase().charCodeAt(0);
                }
                for (var zn5w = 0; zn5w < da1s.length; zn5w++) {
                    if ((evt.shiftKey == ((da1s[zn5w][0] & osye) == osye)) && ((evt.ctrlKey | evt.metaKey) == ((da1s[zn5w][
                            0
                        ] & y69n) == y69n)) && (evt.altKey == ((da1s[zn5w][0] & vh4l) == vh4l)) && (v9vm == da1s[zn5w][
                            1
                        ] || da1s[zn5w][1] == 0)) {
                        jv8c(evt);
                        break;
                    }
                }
            }
        }
        if (document.addEventListener) {
            document.addEventListener("keydown", bn51, true);
            document.addEventListener("keypress", bn51, true);
        } else if (document.attachEvent) {
            document.attachEvent("onkeydown", bn51);
        }
        -->
    </script>
    <meta http-equiv="imagetoolbar" content="no" />

    <style type="text/css" media="print">
        /* Sticky footer styles */
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1000;
            /* Ensure it's on top of other content */
        }

        /* Ensure content is above the footer */
        body {
            margin-bottom: 80px;
            /* Adjust this value based on the height of your footer */
        }
    </style>
    <!--[if gte IE 5]>
      <frame>
      </frame><![endif]-->
</head>

<body class="app sidebar-mini ltr light-mode">
    <div class="horizontalMenucontainer">

        <div class="switcher-wrapper">
            <div class="demo_changer">
                <div class="form_holder sidebar-right1 ps ps--active-y">
                    <div class="row">
                        <div class="predefined_styles">

                            <div class="swichermainleft">
                                <h4>Theme Style</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">Light Theme</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch7"
                                                    id="myonoffswitch6" class="onoffswitch2-checkbox" checked="">
                                                <label for="myonoffswitch6" class="onoffswitch2-label"></label>
                                            </p>
                                        </div>
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">Dark Theme</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch7"
                                                    id="myonoffswitch7" class="onoffswitch2-checkbox" checked="">
                                                <label for="myonoffswitch7" class="onoffswitch2-label"></label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft">
                                <h4>Theme Color</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">Primary Color</span>
                                            <div class=""> <input class=" input-color-picker color-primary-light"
                                                    value="#6259ca" id="colorID" oninput="changePrimaryColor()"
                                                    type="color" data-id="bg-color" data-id1="bg-hover"
                                                    data-id2="bg-border" name="lightPrimary"> </div>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Background Color</span>
                                            <div class=""> <input class="input-bg-picker background-primary-light"
                                                    value="#30304d" id="bgID" oninput="changeBackgroundColor()"
                                                    type="color" data-id3="body" data-id4="theme"
                                                    name="BackgroundPrimary"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft">
                                <h4>Navigation Style</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">Vertical Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch15"
                                                    id="myonoffswitch1" class="onoffswitch2-checkbox" checked="">
                                                <label for="myonoffswitch1" class="onoffswitch2-label"></label>
                                            </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Horizontal Click Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch15"
                                                    id="myonoffswitch2" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch2" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Horizontal Hover Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch15"
                                                    id="myonoffswitch111" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch111" class="onoffswitch2-label"></label> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft">
                                <h4>LTR and RTL VERSIONS</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">LTR Version</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch8"
                                                    id="myonoffswitch4" class="onoffswitch2-checkbox" checked="">
                                                <label for="myonoffswitch4" class="onoffswitch2-label"></label>
                                            </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">RTL Version</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch8"
                                                    id="myonoffswitch5" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch5" class="onoffswitch2-label"></label> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft menu-style">
                                <h4>Header Styles</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle lightHeader d-flex">
                                            <span class="me-auto">Light Header</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch3"
                                                    id="myonoffswitch8" class="onoffswitch2-checkbox" checked="">
                                                <label for="myonoffswitch8" class="onoffswitch2-label"></label>
                                            </p>
                                        </div>
                                        <div class="switch-toggle  colorHeader d-flex mt-2">
                                            <span class="me-auto">Color Header</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch3"
                                                    id="myonoffswitch9" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch9" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle darkHeader d-flex mt-2">
                                            <span class="me-auto">Dark Header</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch3"
                                                    id="myonoffswitch10" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch10" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle darkHeader d-flex mt-2">
                                            <span class="me-auto">Gradient Header</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch3"
                                                    id="myonoffswitch11" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch11" class="onoffswitch2-label"></label> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft menu-style">
                                <h4>Menu Styles</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle lightMenu d-flex">
                                            <span class="me-auto">Light Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch2"
                                                    id="myonoffswitch12" class="onoffswitch2-checkbox"
                                                    checked=""> <label for="myonoffswitch12"
                                                    class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle colorMenu d-flex mt-2">
                                            <span class="me-auto">Color Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch2"
                                                    id="myonoffswitch13" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch13" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle darkMenu d-flex mt-2">
                                            <span class="me-auto">Dark Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch2"
                                                    id="myonoffswitch14" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch14" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle gradientMenu d-flex mt-2">
                                            <span class="me-auto">Gradient Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch2"
                                                    id="myonoffswitch15" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch15" class="onoffswitch2-label"></label> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft">
                                <h4>Layout Width Styles</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">Full Width</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch4"
                                                    id="myonoffswitch16" class="onoffswitch2-checkbox"
                                                    checked=""> <label for="myonoffswitch16"
                                                    class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Boxed</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch4"
                                                    id="myonoffswitch17" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch17" class="onoffswitch2-label"></label> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft">
                                <h4>Layout Positions</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">Fixed</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch5"
                                                    id="myonoffswitch18" class="onoffswitch2-checkbox"
                                                    checked=""> <label for="myonoffswitch18"
                                                    class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Scrollable</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch5"
                                                    id="myonoffswitch19" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch19" class="onoffswitch2-label"></label> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft leftmenu-styles">
                                <h4>Sidemenu layout Styles</h4>
                                <div class="skin-body">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex">
                                            <span class="me-auto">Default Menu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch6"
                                                    id="myonoffswitch20" class="onoffswitch2-checkbox default-menu"
                                                    checked=""> <label for="myonoffswitch20"
                                                    class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Icon with Text</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch6"
                                                    id="myonoffswitch22" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch22" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Icon Overlay</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch6"
                                                    id="myonoffswitch21" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch21" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Closed Sidemenu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch6"
                                                    id="myonoffswitch23" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch23" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Hover Submenu</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch6"
                                                    id="myonoffswitch24" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch24" class="onoffswitch2-label"></label> </p>
                                        </div>
                                        <div class="switch-toggle d-flex mt-2">
                                            <span class="me-auto">Hover Submenu Style 1</span>
                                            <p class="onoffswitch2"><input type="radio" name="onoffswitch6"
                                                    id="myonoffswitch25" class="onoffswitch2-checkbox"> <label
                                                    for="myonoffswitch25" class="onoffswitch2-label"></label> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swichermainleft">
                                <h4>Reset All Styles</h4>
                                <div class="skin-body">
                                    <div class="switch_section my-4"> <button class="btn btn-danger btn-block"
                                            onclick="localStorage.clear();
                              document.querySelector('html').style = '';
                              names() ;
                              resetData() ;"
                                            type="button">Reset All </button> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                    </div>
                    <div class="ps__rail-y" style="top: 0px; height: 739px; right: 0px;">
                        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 317px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Switcher --> <!-- GLOBAL-LOADER -->
        <div id="global-loader" style="display: none;"> <img src="{{ asset('assets') }}/images/loader.svg"
                class="loader-img" alt="Loader"> </div>
        <!-- /GLOBAL-LOADER --> <!-- PAGE -->
        <div class="page">
            <div class="page-main">
                <!-- app-Header -->
                <div class="app-header header sticky" style="margin-bottom: -74px;">
                    <div class="container-fluid main-container">
                        <div class="d-flex align-items-center">
                            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar"
                                href="javascript:void(0);"></a>
                            <div class="responsive-logo"> <a href="index.html" class="header-logo"> <img
                                        src="{{ asset('assets') }}/images/brand/logo-3.png"
                                        class="mobile-logo logo-1" alt="logo"> <img
                                        src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg"
                                        class="mobile-logo dark-logo-1" alt="logo"> </a> </div>
                            <!-- sidebar-toggle--> <a class="logo-horizontal " href="index.html"> <img
                                    src="{{ asset('assets') }}/images/brand/logo.png"
                                    class="header-brand-img desktop-logo" alt="logo"> <img
                                    src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg"
                                    class="header-brand-img light-logo1" alt="logo"> </a> <!-- LOGO -->
                            <div class="main-header-center ms-3 d-none d-lg-block"> <input class="form-control"
                                    placeholder="Search for anything..." type="search"> <button class="btn"><i
                                        class="fa fa-search" aria-hidden="true"></i></button> </div>
                            <div class="d-flex order-lg-2 ms-auto header-right-icons">
                                <!-- SEARCH --> <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarSupportedContent-4"
                                    aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                    aria-label="Toggle navigation"> <span
                                        class="navbar-toggler-icon fe fe-more-vertical text-dark"></span> </button>
                                <div class="navbar navbar-collapse responsive-navbar p-0">
                                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                        <div class="d-flex order-lg-2">
                                            <div class="dropdown d-block d-lg-none">
                                                <a href="javascript:void(0);" class="nav-link icon"
                                                    data-bs-toggle="dropdown"> <i class="fe fe-search"></i> </a>
                                                <div class="dropdown-menu header-search dropdown-menu-start">
                                                    <div class="input-group w-100 p-2">
                                                        <input type="text" class="form-control"
                                                            placeholder="Search....">
                                                        <div class="input-group-text btn btn-primary"> <i
                                                                class="fa fa-search" aria-hidden="true"></i> </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="dropdown d-md-flex"> <a
                                                    class="nav-link icon theme-layout nav-link-bg layout-setting">
                                                    <span class="dark-layout"><i class="fe fe-moon"></i></span> <span
                                                        class="light-layout"><i class="fe fe-sun"></i></span> </a>
                                            </div>
                                            <!-- Theme-Layout -->
                                            <div class="dropdown d-md-flex"> <a
                                                    class="nav-link icon full-screen-link nav-link-bg"> <i
                                                        class="fe fe-minimize fullscreen-button" id="myvideo"></i>
                                                </a> </div>
                                            <!-- FULL-SCREEN -->



                                            <div class="dropdown d-md-flex header-settings"> <a
                                                    href="javascript:void(0);" class="nav-link icon "
                                                    data-bs-toggle="sidebar-right" data-target=".sidebar-right"> <i
                                                        class="fe fe-menu"></i> </a> </div>
                                            <!-- SIDE-MENU -->
                                        </div>
                                    </div>
                                </div>
                                <div class="demo-icon nav-link icon border-0"> <i class="fe fe-settings fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /app-Header --> <!--APP-SIDEBAR-->
                <div class="sticky is-expanded" style="margin-bottom: -74px;">
                    <div class="app-sidebar__overlay active" data-bs-toggle="sidebar"></div>
                    <aside class="app-sidebar open ps ps--active-y">
                        <div class="side-header">
                            <a class="header-brand1" href="index.html"> <img
                                    src="{{ asset('assets') }}/images/brand/logo.png"
                                    class="header-brand-img desktop-logo" alt="logo"> <img
                                    src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg"
                                    class="header-brand-img toggle-logo" alt="logo"> <img
                                    src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg"
                                    class="header-brand-img light-logo" alt="logo"> <img
                                    src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg"
                                    class="header-brand-img light-logo1" alt="logo"> </a> <!-- LOGO -->
                        </div>
                        <div class="main-sidemenu is-expanded">
                            <div class="slide-left disabled active is-expanded" id="slide-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z">
                                    </path>
                                </svg>
                            </div>
                            <ul class="side-menu open" style="margin-right: 0px; margin-left: 0px;">
                                <li class="sub-category">
                                    <h3>Main</h3>
                                </li>
                                <li class="slide is-expanded"> <a class="side-menu__item active"
                                        data-bs-toggle="slide" href="{{ route('admin.home') }}"><i
                                            class="side-menu__icon fe fe-home"></i><span
                                            class="side-menu__label">Dashboard</span></a> </li>
                                <li class="sub-category">
                                    <h3>Accounts</h3>
                                </li>
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                                            class="side-menu__icon fe fe-sliders"></i> <span
                                            class="side-menu__label">Accounts</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">
                                        <li class="side-menu-label1"><a href="javascript:void(0)">Submenus</a></li>

                                        <li class="sub-slide">
                                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide"
                                                href="javascript:void(0);"><span class="sub-side-menu__label">Chart Of
                                                    Accounts</span><i class="sub-angle fa fa-angle-right"></i></a>
                                            <ul class="sub-slide-menu">

                                                @foreach ($grand_parents as $grand)
                                                    @if ($grand->childs->isNotEmpty())
                                                        <li class="sub-slide2">
                                                            <a class="sub-side-menu__item2" href="javascript:void(0);"
                                                                data-bs-toggle="sub-slide2"><span
                                                                    class="sub-side-menu__label2">{{ $grand->name }}</span><i
                                                                    class="sub-angle2 fa fa-angle-right"></i></a>
                                                            <ul class="sub-slide-menu2">
                                                                @foreach ($grand->childs()->get() as $child)
                                                                    <li><a href="{{ route('admin.accounts.add', ['grand_parent_id' => $grand->hashid, 'parent_id' => $child->hashid]) }}"
                                                                            class="sub-slide-item2">{{ $child->name }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                                <li class="slide">
                                    <a class="side-menu__item " data-bs-toggle="slide" href="javascript:void(0);"><i
                                            class="side-menu__icon fe fe-file"></i><span
                                            class="side-menu__label">CashBook</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">
                                        <li class="side-menu-label1"><a href="javascript:void(0);">CashBook</a></li>
                                        <li><a href="{{ route('admin.cash.index') }}" class="slide-item"> Add
                                                CashBook</a></li>
                                    </ul>
                                </li>
                                <li> <a class="side-menu__item" href="{{ route('admin.paymentbooks.index') }}"><i
                                            class="side-menu__icon fe fe-grid"></i><span
                                            class="side-menu__label">Payment Book</span></a> </li>

                                <li class="sub-category">
                                    <h3>Inventory Items</h3>
                                </li>
                                <!-- <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i
                                            class="side-menu__icon fe fe-file"></i><span
                                            class="side-menu__label">Category</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">
                                        <li class="side-menu-label1"><a href="javascript:void(0);">Category</a></li>
                                        <li><a href="{{ route('admin.categories.index') }}" class="slide-item"> Add
                                                Category</a></li>

                                    </ul>
                                </li> -->
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i
                                            class="side-menu__icon fe fe-file"></i><span
                                            class="side-menu__label">Company</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">
                                        <li class="side-menu-label1"><a href="javascript:void(0);">Company</a></li>
                                        <li><a href="{{ route('admin.companys.index') }}" class="slide-item"> Add
                                                Company</a></li>

                                    </ul>
                                </li>
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i
                                            class="side-menu__icon fe fe-file"></i><span
                                            class="side-menu__label">Items</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">
                                        <li class="side-menu-label1"><a href="javascript:void(0);">Items</a></li>
                                        <li><a href="{{ route('admin.items.add') }}" class="slide-item"> Add
                                                Items</a></li>
                                        <li><a href="{{ route('admin.items.index') }}" class="slide-item"> All
                                                Items</a></li>


                                    </ul>
                                </li>
                                <li class="slide">
                                    <a class="side-menu__item {{ request()->is('web_admin/chick-invoices/purchase') || request()->is('web_admin/chick-invoices/sale') ? 'active' : '' }}"
                                        data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-qq"
                                            aria-hidden="true"></i> &nbsp&nbsp&nbsp <span
                                            class="side-menu__label">Chicks</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">

                                        <li><a href="{{ route('admin.chick-invoices.purchase') }}"
                                                class="slide-item  {{ request()->is('web_admin/chick-invoices/purchase') ? 'active open' : '' }}">
                                                Purchase Chick</a></li>
                                        <li><a href="{{ route('admin.chick-invoices.sale') }}"
                                                class="slide-item {{ request()->is('web_admin/chick-invoices/sale') ? 'active open' : '' }}">
                                                Sale
                                                Chick</a></li>


                                    </ul>
                                </li>
                                <!-- Feed -->
                                <li class="slide">
                                    <a class="side-menu__item {{ request()->is('web_admin/feed-invoices/purchase') || request()->is('web_admin/feed-invoices/sale') ? 'active' : '' }}"
                                        data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-square"
                                            aria-hidden="true"></i>&nbsp&nbsp&nbsp <span
                                            class="side-menu__label">Feeds</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">

                                        <li><a href="{{ route('admin.feed-invoices.purchase') }}"
                                                class="slide-item {{ request()->is('web_admin/feed-invoices/purchase') ? 'active open' : '' }}">
                                                Purchase Feed</a></li>
                                        <li><a href="{{ route('admin.feed-invoices.sale') }}"
                                                class="slide-item {{ request()->is('web_admin/feed-invoices/sale') ? 'active open' : '' }}">
                                                Sale
                                                Feed</a></li>


                                    </ul>
                                </li>
                                <!-- Medicine -->
                                <li class="slide">
                                    <a class="side-menu__item {{ request()->is('web_admin/medicine-invoices/purchase') || request()->is('web_admin/medicine-invoices/sale') ? 'active' : '' }}"
                                        data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-medkit"
                                            aria-hidden="true"></i> &nbsp&nbsp&nbsp<span
                                            class="side-menu__label">Medicine</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">

                                        <li><a href="{{ route('admin.medicine-invoices.purchase') }}"
                                                class="slide-item {{ request()->is('web_admin/medicine-invoices/purchase') ? 'active open' : '' }}">
                                                Purchase Medicine</a></li>
                                        <li><a href="{{ route('admin.medicine-invoices.sale') }}"
                                                class="slide-item {{ request()->is('web_admin/medicine-invoices/sale') ? 'active open' : '' }}">
                                                Sale Medicine</a></li>


                                    </ul>
                                </li>
                                <!-- Murghi -->
                                <li class="slide">
                                    <a class="side-menu__item {{ request()->is('web_admin/murghi-invoices/purchase') || request()->is('web_admin/murghi-invoices/sale') ? 'active' : '' }}"
                                        data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-android"
                                            aria-hidden="true"></i> &nbsp&nbsp&nbsp<span
                                            class="side-menu__label">Murghi</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">

                                        <li><a href="{{ route('admin.murghi-invoices.purchase') }}"
                                                class="slide-item {{ request()->is('web_admin/murghi-invoices/prchase') ? 'active open' : '' }}">
                                                Purchase Murghi</a></li>
                                        <li><a href="{{ route('admin.murghi-invoices.sale') }}"
                                                class="slide-item {{ request()->is('web_admin/murghi-invoices/sale  ') ? 'active open' : '' }}">
                                                Sale Murghi</a></li>
                                        <!-- <li><a href="{{ route('admin.reports.all_report', ['id' => 'purchase_murghi']) }}" class="slide-item"> Purchase Murghi Report</a></li>
                              <li><a href="{{ route('admin.reports.all_report', ['id' => 'sale_murghi']) }}" class="slide-item"> Sale Murghi Report</a></li> -->

                                    </ul>
                                </li>
                                <!-- Other -->
                                <li class="slide">
                                    <a class="side-menu__item {{ request()->is('web_admin/other-invoices/purchase') || request()->is('web_admin/other-invoices/sale') ? 'active' : '' }}"
                                        data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-file-text"
                                            aria-hidden="true"></i> &nbsp&nbsp&nbsp<span
                                            class="side-menu__label">Others Items</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">

                                        <li><a href="{{ route('admin.other-invoices.purchase') }}"
                                                class="slide-item {{ request()->is('web_admin/other-invoices/purchase') ? 'active open' : '' }}">
                                                Purchase Other</a></li>
                                        <li><a href="{{ route('admin.other-invoices.sale') }}"
                                                class="slide-item {{ request()->is('web_admin/other-invoices/sale') ? 'active open' : '' }}">
                                                Sale Other</a></li>


                                    </ul>
                                </li>
                                <li> <a class="side-menu__item" href="{{ route('admin.stock.index') }}"><i
                                            class="side-menu__icon fe fe-grid"></i><span
                                            class="side-menu__label">Available Stock</span></a> </li>

                                <li class="sub-category">
                                    <h3>Reports</h3>
                                </li>

                                <!-- Feed -->
                                <li class="slide">
                                    <a class="side-menu__item {{ request()->is('web_admin/report/Reports/*') ? 'active' : '' }}"
                                        data-bs-toggle="slide" href="javascript:void(0);"><i class="fa fa-square"
                                            aria-hidden="true"></i>&nbsp&nbsp&nbsp <span
                                            class="side-menu__label">Reports</span><i
                                            class="angle fa fa-angle-right"></i></a>
                                    <ul class="slide-menu">

                                        <li><a href="{{ route('admin.reports.daybook_report') }}" class="slide-item">
                                                DayBook Report</a></li>
                                        <li><a href="{{ route('admin.reports.cashflowreport') }}" class="slide-item">
                                                CashFlow Report</a></li>
                                        <li><a href="{{ route('admin.reports.account') }}" class="slide-item"> Party
                                                Account Ledger</a></li>
                                        <li><a href="{{ route('admin.reports.item_stock_report') }}"
                                                class="slide-item">Items Stock Report </a></li>

                                        <li><a href="{{ route('admin.reports.accounts_head_report') }}"
                                                class="slide-item">Accounts Head Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'Purchase_murghi']) }}"
                                                class="slide-item"> Purchase Feed Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'sale_murghi']) }}"
                                                class="slide-item"> Sale Feed Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'purchase_chick']) }}"
                                                class="slide-item"> Purchase Chick Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'sale_chick']) }}"
                                                class="slide-item"> Sale Chick Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'purchase_feed']) }}"
                                                class="slide-item"> Purchase Feed Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'sale_feed']) }}"
                                                class="slide-item"> Sale Feed Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'sale_return_feed']) }}"
                                                class="slide-item">Sale Return Feed Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'purchase_return_feed']) }}"
                                                class="slide-item"> Sale Return Feed Report</a></li>

                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'purchase_medicine']) }}"
                                                class="slide-item"> Purchase Medicine Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'sale_medicine']) }}"
                                                class="slide-item"> Sale Medicine Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'purchase_return']) }}"
                                                class="slide-item">Purchase Return Medicine Report</a></li>

                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'sale_return']) }}"
                                                class="slide-item">Sale Return Medicine Report</a></li>
                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'medicine_adjust_in']) }}"
                                                class="slide-item">Adjust In Medicine Report</a></li>

                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'medicine_adjust_out']) }}"
                                                class="slide-item">Adjust Out Medicine Report</a></li>

                                        <li><a href="{{ route('admin.reports.all_report', ['id' => 'expire_medicine']) }}"
                                                class="slide-item"> Expire Medicine Report</a></li>

                                    </ul>
                                </li>

                                <li class="sub-category">
                                    <h3>Inventory Stocks</h3>
                                </li>
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i
                                            class="side-menu__icon fe fe-file-text"></i><span
                                            class="side-menu__label">Stock & Inventory</span><span
                                            class="badge bg-success side-badge">5</span><i
                                            class="angle fa fa-angle-right hor-rightangle"></i></a>
                                    <ul class="slide-menu">
                                        <li class="side-menu-label1"><a href="javascript:void(0)">Stock &
                                                Inventory</a></li>
                                        <li><a href="{{ route('admin.reports.feed_item_wise_stock_report') }}"
                                                class="slide-item">Feed Itemwise Stock Report </a></li>
                                        <li><a href="{{ route('admin.reports.chick_item_wise_stock_report') }}"
                                                class="slide-item">Chick Itemwise Stock Report </a></li>
                                        <li><a href="{{ route('admin.reports.murghi_item_wise_stock_report') }}"
                                                class="slide-item">Murghi Itemwise Stock Report </a></li>
                                        <li><a href="{{ route('admin.reports.medicine_item_wise_stock_report') }}"
                                                class="slide-item">Medicine Itemwise Stock Report </a></li>
                                    </ul>
                                </li>

                            </ul>
                            <div class="slide-right" id="slide-right">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; height: 739px; right: 0px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 576px;"></div>
                        </div>
                    </aside>
                </div>

                <!--/APP-SIDEBAR--> <!--app-content open-->
                @yield('content')
                <!--app-content end-->
            </div>
            <!-- Sidebar-right -->
            <div class="sidebar sidebar-right sidebar-animate ps">
                <div class="panel panel-primary card mb-0 shadow-none border-0">
                    <div class="tab-menu-heading border-0 d-flex p-3">
                        <div class="card-title mb-0">Notifications</div>
                        <div class="card-options ms-auto"> <a href="javascript:void(0);"
                                class="sidebar-icon text-end float-end me-1" data-bs-toggle="sidebar-right"
                                data-target=".sidebar-right"><i class="fe fe-x text-white"></i></a> </div>
                    </div>
                    <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
                        <div class="tabs-menu border-bottom">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class=""><a href="#side1" class="active" data-bs-toggle="tab"><i
                                            class="fe fe-user me-1"></i> Profile</a></li>
                                <li><a href="#side2" data-bs-toggle="tab"><i class="fe fe-users me-1"></i>
                                        Contacts</a></li>
                                <li><a href="#side3" data-bs-toggle="tab"><i class="fe fe-settings me-1"></i>
                                        Settings</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="side1">
                                <div class="card-body text-center">
                                    <div class="dropdown user-pro-body">
                                        <div class=""> <img alt="user-img"
                                                class="avatar avatar-xl brround mx-auto text-center"
                                                src="{{ asset('assets') }}/images/faces/6.jpg"><span
                                                class="avatar-status profile-status bg-green"></span> </div>
                                        <div class="user-info mg-t-20">
                                            <h6 class="fw-semibold  mt-2 mb-0">Mintrona Pechon</h6>
                                            <span class="mb-0 text-muted fs-12">Premium Member</span>
                                        </div>
                                    </div>
                                </div>
                                <a class="dropdown-item d-flex border-bottom border-top" href="profile.html">
                                    <div class="d-flex">
                                        <i class="fe fe-user me-3 tx-20 text-muted"></i>
                                        <div class="pt-1">
                                            <h6 class="mb-0">My Profile</h6>
                                            <p class="tx-12 mb-0 text-muted">Profile Personal information</p>
                                        </div>
                                    </div>
                                </a>
                                
                                <a class="dropdown-item d-flex border-bottom" href="editprofile.html">
                                    <div class="d-flex">
                                        <i class="fe fe-settings me-3 tx-20 text-muted"></i>
                                        <div class="pt-1">
                                            <h6 class="mb-0">Account Settings</h6>
                                            <p class="tx-12 mb-0 text-muted">Settings Information</p>
                                        </div>
                                    </div>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <a class="dropdown-item d-flex border-bottom" href="{{ route('logout') }}" onclick="logout(event)">
                                    <div class="d-flex">
                                        <i class="fe fe-power me-3 tx-20 text-muted"></i>
                                        <div class="pt-1">
                                            <h6 class="mb-0">Sign Out</h6>
                                            <p class="tx-12 mb-0 text-muted">Account Signout</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="tab-pane" id="side2">
                                <div class="list-group list-group-flush ">
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/9.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/9.jpg&quot;) center center;"><span
                                                    class="avatar-status bg-success"></span></span> </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Mozelle Belt</div>
                                            <p class="mb-0 tx-12 text-muted">mozellebelt@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/11.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/11.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Florinda Carasco</div>
                                            <p class="mb-0 tx-12 text-muted">florindacarasco@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/10.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/10.jpg&quot;) center center;"><span
                                                    class="avatar-status bg-success"></span></span> </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Alina Bernier</div>
                                            <p class="mb-0 tx-12 text-muted">alinaaernier@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/2.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/2.jpg&quot;) center center;"><span
                                                    class="avatar-status bg-success"></span></span> </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Zula Mclaughin</div>
                                            <p class="mb-0 tx-12 text-muted">zulamclaughin@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/13.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/13.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Isidro Heide</div>
                                            <p class="mb-0 tx-12 text-muted">isidroheide@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/12.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/12.jpg&quot;) center center;"><span
                                                    class="avatar-status bg-success"></span></span> </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Mozelle Belt</div>
                                            <p class="mb-0 tx-12 text-muted">mozellebelt@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/4.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/4.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Florinda Carasco</div>
                                            <p class="mb-0 tx-12 text-muted">florindacarasco@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/7.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/7.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Alina Bernier</div>
                                            <p class="mb-0 tx-12 text-muted">alinabernier@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/2.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/2.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Zula Mclaughin</div>
                                            <p class="mb-0 tx-12 text-muted">zulamclaughin@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/14.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/14.jpg&quot;) center center;"><span
                                                    class="avatar-status bg-success"></span></span> </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Isidro Heide</div>
                                            <p class="mb-0 tx-12 text-muted">isidroheide@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/11.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/11.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Florinda Carasco</div>
                                            <p class="mb-0 tx-12 text-muted">florindacarasco@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/9.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/9.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Alina Bernier</div>
                                            <p class="mb-0 tx-12 text-muted">alinabernier@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/15.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/15.jpg&quot;) center center;"><span
                                                    class="avatar-status bg-success"></span></span> </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Zula Mclaughin</div>
                                            <p class="mb-0 tx-12 text-muted">zulamclaughin@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex  align-items-center">
                                        <div class="me-2"> <span class="avatar avatar-md brround cover-image"
                                                data-bs-image-src="{{ asset('assets') }}/images/faces/4.jpg"
                                                style="background: url(&quot;{{ asset('assets') }}/images/faces/4.jpg&quot;) center center;"></span>
                                        </div>
                                        <div class="">
                                            <div class="fw-semibold" data-bs-toggle="modal" data-target="#chatmodel">
                                                Isidro Heide</div>
                                            <p class="mb-0 tx-12 text-muted">isidroheide@gmail.com</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="side3">
                                <a class="dropdown-item bg-gray-100 pd-y-10" href="javascript:void(0);"> Account
                                    Settings </a>
                                <div class="card-body">
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input" checked=""> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Updates Automatically</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input"> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Allow Location Map</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input" checked=""> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Show Contacts</span> </label>
                                    </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input" checked=""> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Show Notication</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input"> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Show Tasks Statistics</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input" checked=""> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Show Email
                                                Notification</span> </label> </div>
                                </div>
                                <a class="dropdown-item bg-gray-100 pd-y-10" href="javascript:void(0);"> General
                                    Settings </a>
                                <div class="card-body">
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input" checked=""> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Show User Online</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input"> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Website Notication</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input"> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Show Recent activity</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input"> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Logout Automatically</span>
                                        </label> </div>
                                    <div class="form-group mg-b-10"> <label class="custom-switch ps-0"> <input
                                                type="checkbox" name="custom-switch-checkbox"
                                                class="custom-switch-input" checked=""> <span
                                                class="custom-switch-indicator"></span> <span
                                                class="custom-switch-description mg-l-10">Allow All
                                                Notifications</span> </label> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                </div>
                <div class="ps__rail-y" style="top: 0px; right: 0px;">
                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                </div>
            </div>
            <!--/Sidebar-right--> <!-- FOOTER -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Footer content here -->
                        </div>
                        <div class="col-md-6">
                            <!-- Footer content here -->
                        </div>
                    </div>
                </div>
            </footer>

            <!-- FOOTER END -->
        </div>

    </div>
    <!-- BACK-TO-TOP --> <a href="#top" id="back-to-top" style="display: none;"><i
            class="fa fa-angle-up"></i></a> <!-- JQUERY JS -->

    <script src="{{ asset('assets') }}/js/jquery.min.js"></script>
    <script type="text/javascript"></script> <!-- BOOTSTRAP JS -->

    <script src="{{ asset('assets') }}/plugins/bootstrap/js/popper.min.js"></script>
    <script type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript"></script> <!-- SPARKLINE JS-->

    <script src="{{ asset('assets') }}/js/jquery.sparkline.min.js"></script>
    <script type="text/javascript"></script> <!-- CHART-CIRCLE JS-->

    <script src="{{ asset('assets') }}/js/circle-progress.min.js"></script>
    <script type="text/javascript"></script> <!-- CHARTJS CHART JS-->

    <script src="{{ asset('assets') }}/plugins/chart/Chart.bundle.js"></script>
    <script type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/chart/utils.js"></script>
    <script type="text/javascript"></script> <!-- PIETY CHART JS-->

    <script src="{{ asset('assets') }}/plugins/peitychart/jquery.peity.min.js"></script>
    <script type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/peitychart/peitychart.init.js"></script>
    <script type="text/javascript"></script> <!-- INTERNAL SELECT2 JS -->

    <script src="{{ asset('assets') }}/plugins/select2/select2.full.min.js"></script>
    <script type="text/javascript"></script> <!-- INTERNAL Data tables js-->

    <script src="{{ asset('assets') }}/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/datatable/dataTables.responsive.min.js"></script>
    <script type="text/javascript"></script> <!-- ECHART JS-->

    <script src="{{ asset('assets') }}/plugins/echarts/echarts.js"></script>
    <script type="text/javascript"></script> <!-- SIDE-MENU JS-->

    <script src="{{ asset('assets') }}/plugins/sidemenu/sidemenu.js"></script>
    <script type="text/javascript"></script> <!-- Sticky js -->

    <script src="{{ asset('assets') }}/js/sticky.js"></script>
    <script type="text/javascript"></script> <!-- SIDEBAR JS -->

    <script src="{{ asset('assets') }}/plugins/sidebar/sidebar.js"></script>
    <script type="text/javascript"></script> <!-- Perfect SCROLLBAR JS-->

    <script src="{{ asset('assets') }}/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/p-scroll/pscroll.js"></script>
    <script type="text/javascript"></script>

    <script src="{{ asset('assets') }}/plugins/p-scroll/pscroll-1.js"></script>
    <script type="text/javascript"></script> <!-- APEXCHART JS -->

    <script src="{{ asset('assets') }}/js/apexcharts.js"></script>
    <script type="text/javascript"></script> <!-- INDEX JS -->

    <script src="{{ asset('assets') }}/js/index1.js"></script>
    <script type="text/javascript"></script> <!-- Color Theme js -->

    <script src="{{ asset('assets') }}/js/themeColors.js"></script>
    <script type="text/javascript"></script>
    <svg id="SvgjsSvg1177" width="2" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1"
        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
        style="overflow: hidden; top: -100%; left: -100%; position: absolute; opacity: 0;">
        <defs id="SvgjsDefs1178"></defs>
        <polyline id="SvgjsPolyline1179" points="0,0"></polyline>
        <path id="SvgjsPath1180"
            d="M-1 234.99519938278195L-1 234.99519938278195C-1 234.99519938278195 71.7368253361095 234.99519938278195 71.7368253361095 234.99519938278195C71.7368253361095 234.99519938278195 143.473650672219 234.99519938278195 143.473650672219 234.99519938278195C143.473650672219 234.99519938278195 215.21047600832853 234.99519938278195 215.21047600832853 234.99519938278195C215.21047600832853 234.99519938278195 286.947301344438 234.99519938278195 286.947301344438 234.99519938278195C286.947301344438 234.99519938278195 358.68412668054754 234.99519938278195 358.68412668054754 234.99519938278195C358.68412668054754 234.99519938278195 430.42095201665705 234.99519938278195 430.42095201665705 234.99519938278195C430.42095201665705 234.99519938278195 502.1577773527666 234.99519938278195 502.1577773527666 234.99519938278195C502.1577773527666 234.99519938278195 573.894602688876 234.99519938278195 573.894602688876 234.99519938278195C573.894602688876 234.99519938278195 645.6314280249856 234.99519938278195 645.6314280249856 234.99519938278195C645.6314280249856 234.99519938278195 717.3682533610951 234.99519938278195 717.3682533610951 234.99519938278195C717.3682533610951 234.99519938278195 789.1050786972046 234.99519938278195 789.1050786972046 234.99519938278195C789.1050786972046 234.99519938278195 789.1050786972046 234.99519938278195 789.1050786972046 234.99519938278195 ">
        </path>
    </svg>
    <!-- swither styles js -->

    <script src="{{ asset('assets') }}/js/swither-styles.js"></script>
    <script type="text/javascript"></script> <!-- CUSTOM JS -->

    <script src="{{ asset('assets') }}/js/custom.js"></script>
    <script type="text/javascript"></script> <!-- Switcher js -->

    <script src="{{ asset('assets') }}/switcher/js/switcher.js"></script>
    <!-- Vendor JS -->
    <script src="{{ asset('new_assets') }}/js/vendors.min.js"></script>
    <script src="{{ asset('new_assets') }}/js/pages/chat-popup.js"></script>
    <script src="{{ asset('new_assets') }}/assets/icons/feather-icons/feather.min.js"></script>

    <!-- Lion Admin App -->
    <script src="{{ asset('new_assets') }}/js/demo.js"></script>
    <script src="{{ asset('new_assets') }}/js/jquery.smartmenus.js"></script>
    <script src="{{ asset('new_assets') }}/js/menus.js"></script>
    <script src="{{ asset('new_assets') }}/js/template.js"></script>
    <script
        src="{{ asset('new_assets') }}/assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js">
    </script>
    <script
        src="{{ asset('new_assets') }}/assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js">
    </script>
    <script src="{{ asset('new_assets') }}/assets/vendor_components/datatable/datatables.min.js"></script>

    <script src="{{ asset('new_assets') }}/js/pages/data-table.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('new_assets') }}/js/pages/validation.js"></script>
    <script src="{{ asset('new_assets') }}/js/pages/form-validation.js"></script>
    <script src="{{ asset('new_assets') }}/js/pages/advanced-form-element.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    <script src="{{ asset('admin_assets') }}/js/custom.js"></script>
    @yield('page-scripts')
</body>

</html>
