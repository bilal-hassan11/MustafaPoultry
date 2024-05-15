<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bilal Feed | Inward Details</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('weighbridge_assets') }}/img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/font-awesome.min.css">
	<!-- nalika Icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/nalika-icon.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/owl.carousel.css">
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/owl.theme.css">
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/owl.transitions.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/normalize.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/meanmenu.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/main.css">
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/morrisjs/morris.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/metisMenu/metisMenu.min.css">
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/metisMenu/metisMenu-vertical.css">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/calendar/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/calendar/fullcalendar.print.min.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('weighbridge_assets') }}/css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/vendor/modernizr-2.8.3.min.js"></script>
    <!-- select 2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            
			<div class="nalika-profile">
				<div class="profile-dtl">
					<a href="#"><img src="{{ asset('weighbridge_assets') }}/img/main-logo.jpeg" alt="" /></a>
					<h2>Weight<span class="min-dtn">Bridge</span></h2>
				</div>
				<!-- <div class="profile-social-dtl">
					<ul class="dtl-social">
						<li><a href="#"><i class="icon nalika-facebook"></i></a></li>
						<li><a href="#"><i class="icon nalika-twitter"></i></a></li>
						<li><a href="#"><i class="icon nalika-linkedin"></i></a></li>
					</ul>
				</div> -->
			</div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1">
                        <li class="active">
                            <a class="" href="index.html">
								   <i class="icon nalika-home icon-wrap"></i>
								   <span class="mini-click-non">Dashboard</span>
								</a>
                            <ul class="" aria-expanded="true">
                                <!-- <li><a title="Dashboard v.1" href="index.html"><span class="mini-sub-pro">Dashboard v.1</span></a></li>
                                <li><a title="Dashboard v.2" href="index-1.html"><span class="mini-sub-pro">Dashboard v.2</span></a></li>
                                <li><a title="Dashboard v.3" href="index-2.html"><span class="mini-sub-pro">Dashboard v.3</span></a></li>
                                <li><a title="Product List" href="product-list.html"><span class="mini-sub-pro">Product List</span></a></li>
                                <li><a title="Product Edit" href="product-edit.html"><span class="mini-sub-pro">Product Edit</span></a></li>
                                <li><a title="Product Detail" href="product-detail.html"><span class="mini-sub-pro">Product Detail</span></a></li>
                                <li><a title="Product Cart" href="product-cart.html"><span class="mini-sub-pro">Product Cart</span></a></li>
                                <li><a title="Product Payment" href="product-payment.html"><span class="mini-sub-pro">Product Payment</span></a></li>
                                <li><a title="Analytics" href="analytics.html"><span class="mini-sub-pro">Analytics</span></a></li>
                                <li><a title="Widgets" href="widgets.html"><span class="mini-sub-pro">Widgets</span></a></li> -->
                            </ul>
                        </li>
                        <!-- <li>
                            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-mail icon-wrap"></i> <span class="mini-click-non">Mailbox</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Inbox" href="mailbox.html"><span class="mini-sub-pro">Inbox</span></a></li>
                                <li><a title="View Mail" href="mailbox-view.html"><span class="mini-sub-pro">View Mail</span></a></li>
                                <li><a title="Compose Mail" href="mailbox-compose.html"><span class="mini-sub-pro">Compose Mail</span></a></li>
                            </ul>
                        </li> -->
                        <!-- <li>
                            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-diamond icon-wrap"></i> <span class="mini-click-non">Interface</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Google Map" href="google-map.html"><span class="mini-sub-pro">Google Map</span></a></li>
                                <li><a title="Data Maps" href="data-maps.html"><span class="mini-sub-pro">Data Maps</span></a></li>
                                <li><a title="Pdf Viewer" href="pdf-viewer.html"><span class="mini-sub-pro">Pdf Viewer</span></a></li>
                                <li><a title="X-Editable" href="x-editable.html"><span class="mini-sub-pro">X-Editable</span></a></li>
                                <li><a title="Code Editor" href="code-editor.html"><span class="mini-sub-pro">Code Editor</span></a></li>
                                <li><a title="Tree View" href="tree-view.html"><span class="mini-sub-pro">Tree View</span></a></li>
                                <li><a title="Preloader" href="preloader.html"><span class="mini-sub-pro">Preloader</span></a></li>
                                <li><a title="Images Cropper" href="images-cropper.html"><span class="mini-sub-pro">Images Cropper</span></a></li>
                            </ul>
                        </li> -->
                        <li>
                            <a  href="../public/inward.html" aria-expanded="false"><i class="icon nalika-bar-chart icon-wrap"></i> <span class="mini-click-non">Inward</span></a>
                            <!-- <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Bar Charts" href="bar-charts.html"><span class="mini-sub-pro">Bar Charts</span></a></li>
                                <li><a title="Line Charts" href="line-charts.html"><span class="mini-sub-pro">Line Charts</span></a></li>
                                <li><a title="Area Charts" href="area-charts.html"><span class="mini-sub-pro">Area Charts</span></a></li>
                                <li><a title="Rounded Charts" href="rounded-chart.html"><span class="mini-sub-pro">Rounded Charts</span></a></li>
                                <li><a title="C3 Charts" href="c3.html"><span class="mini-sub-pro">C3 Charts</span></a></li>
                                <li><a title="Sparkline Charts" href="sparkline.html"><span class="mini-sub-pro">Sparkline Charts</span></a></li>
                                <li><a title="Peity Charts" href="peity.html"><span class="mini-sub-pro">Peity Charts</span></a></li>
                            </ul> -->
                        </li>
                        <!-- Outward -->
                        <li>
                            <a  href="../public/outward.html" aria-expanded="false"><i class="icon nalika-bar-chart icon-wrap"></i> <span class="mini-click-non">Outward</span></a>
                            <!-- <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Bar Charts" href="bar-charts.html"><span class="mini-sub-pro">Bar Charts</span></a></li>
                                <li><a title="Line Charts" href="line-charts.html"><span class="mini-sub-pro">Line Charts</span></a></li>
                                <li><a title="Area Charts" href="area-charts.html"><span class="mini-sub-pro">Area Charts</span></a></li>
                                <li><a title="Rounded Charts" href="rounded-chart.html"><span class="mini-sub-pro">Rounded Charts</span></a></li>
                                <li><a title="C3 Charts" href="c3.html"><span class="mini-sub-pro">C3 Charts</span></a></li>
                                <li><a title="Sparkline Charts" href="sparkline.html"><span class="mini-sub-pro">Sparkline Charts</span></a></li>
                                <li><a title="Peity Charts" href="peity.html"><span class="mini-sub-pro">Peity Charts</span></a></li>
                            </ul> -->
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-pie-chart icon-wrap"></i> <span class="mini-click-non">Reports</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="File Manager" href=""><span class="mini-sub-pro">Inward Report </span></a></li>
                                <li><a title="Blog" href=""><span class="mini-sub-pro">Outward Report</span></a></li>
                                <!-- <li><a title="Blog Details" href="blog-details.html"><span class="mini-sub-pro">Blog Details</span></a></li>
                                <li><a title="404 Page" href="404.html"><span class="mini-sub-pro">404 Page</span></a></li>
                                <li><a title="500 Page" href="500.html"><span class="mini-sub-pro">500 Page</span></a></li> -->
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="icon nalika-settings author-log-ic"></span>&nbsp; Settings</a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Buttons" href="http://localhost/hyderabad-feed/inward-report"><span class="mini-sub-pro">General Setting</span></a></li>
                                <li><a title="Tabs" href="http://localhost/hyderabad-feed/outward-report"><span class="mini-sub-pro">Language Setting</span></a></li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-table icon-wrap"></i> <span class="mini-click-non">Data Tables</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Peity Charts" href="static-table.html"><span class="mini-sub-pro">Static Table</span></a></li>
                                <li><a title="Data Table" href="data-table.html"><span class="mini-sub-pro">Data Table</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-forms icon-wrap"></i> <span class="mini-click-non">Forms Elements</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Basic Form Elements" href="basic-form-element.html"><span class="mini-sub-pro">Bc Form Elements</span></a></li>
                                <li><a title="Advance Form Elements" href="advance-form-element.html"><span class="mini-sub-pro">Ad Form Elements</span></a></li>
                                <li><a title="Password Meter" href="password-meter.html"><span class="mini-sub-pro">Password Meter</span></a></li>
                                <li><a title="Multi Upload" href="multi-upload.html"><span class="mini-sub-pro">Multi Upload</span></a></li>
                                <li><a title="Text Editor" href="tinymc.html"><span class="mini-sub-pro">Text Editor</span></a></li>
                                <li><a title="Dual List Box" href="dual-list-box.html"><span class="mini-sub-pro">Dual List Box</span></a></li>
                            </ul>
                        </li> -->
                        <!-- <li>
                            <a class="has-arrow" href="mailbox.html" aria-expanded="false"><i class="icon nalika-smartphone-call icon-wrap"></i> <span class="mini-click-non">App views</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Notifications" href="notifications.html"><span class="mini-sub-pro">Notifications</span></a></li>
                                <li><a title="Alerts" href="alerts.html"><span class="mini-sub-pro">Alerts</span></a></li>
                                <li><a title="Modals" href="modals.html"><span class="mini-sub-pro">Modals</span></a></li>
                                <li><a title="Buttons" href="buttons.html"><span class="mini-sub-pro">Buttons</span></a></li>
                                <li><a title="Tabs" href="tabs.html"><span class="mini-sub-pro">Tabs</span></a></li>
                                <li><a title="Accordion" href="accordion.html"><span class="mini-sub-pro">Accordion</span></a></li>
                            </ul>
                        </li>
                        <li id="removable">
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="icon nalika-new-file icon-wrap"></i> <span class="mini-click-non">Pages</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Login" href="login.html"><span class="mini-sub-pro">Login</span></a></li>
                                <li><a title="Register" href="register.html"><span class="mini-sub-pro">Register</span></a></li>
                                <li><a title="Lock" href="lock.html"><span class="mini-sub-pro">Lock</span></a></li>
                                <li><a title="Password Recovery" href="password-recovery.html"><span class="mini-sub-pro">Password Recovery</span></a></li>
                            </ul>
                        </li> -->
                    </ul>
                </nav>
            </div>
        </nav>
    </div>
    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="index.html"><img class="main-logo" src="{{ asset('weighbridge_assets') }}/img/logo/logo.png" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="header-top-wraper">
                      <div class="row">
                        <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                          <div class="menu-switcher-pro">
                            <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                              <i class="icon nalika-menu-task"></i>
                            </button>
                          </div>
                        </div>
                        
                        <div class="col-lg-8 col-md-5 col-sm-12 col-xs-12">
                            <div class="header-right-info">
                                <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                    
                                    <li class="nav-item">
                                      <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                        <i class="icon nalika-bar-chart icon-wrap"></i>
                                        <span class="admin-name">Inward</span>
                                        <i class="icon nalika-down-arrow nalika-angle-dw nalika-icon"></i>
                                      </a>
                                      <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                          <li><a href="../public/inward.html"><span class="icon nalika-unlocked author-log-ic"></span> Add Inward</a>
                                          </li>
                                      </ul>
                                    </li>
                                    <li class="nav-item">
                                      <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                        <i class="icon nalika-bar-chart icon-wrap"></i> 
                                        <span class="admin-name">Outward</span>
                                        <i class="icon nalika-down-arrow nalika-angle-dw nalika-icon"></i>
                                      </a>
                                      <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                          <li><a href="../public/outward.html"><span class="icon nalika-unlocked author-log-ic"></span> Add Outward</a>
                                          </li>
                                      </ul>
                                    </li>
                                    <li class="nav-item">
                                      <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                        <i class="icon nalika-pie-chart icon-wrap"></i>
                                        <span class="admin-name">Reports</span>
                                        <i class="icon nalika-down-arrow nalika-angle-dw nalika-icon"></i>
                                      </a>
                                      <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                        <li><a title="File Manager" href="http://localhost/hyderabad_feed/public/outward/inward_report"><span class="mini-sub-pro">Inward Report </span></a></li>
                                        <li><a title="File Manager" href="http://localhost/hyderabad_feed/public/outward/outward_report"><span class="mini-sub-pro">Outward Report </span></a></li>
                                
                                      </ul>
                                    </li>
                                    <!-- <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-alarm" aria-hidden="true"></i><span class="indicator-nt"></span></a>
                                        <div role="menu" class="notification-author dropdown-menu animated zoomIn">
                                            <div class="notification-single-top">
                                                <h1>Notifications</h1>
                                            </div>
                                            <ul class="notification-menu">
                                                <li>
                                                    <a href="#">
                                                        <div class="notification-icon">
                                                            <i class="icon nalika-tick" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="notification-content">
                                                            <span class="notification-date">16 Sept</span>
                                                            <h2>Advanda Cro</h2>
                                                            <p>Please done this project as soon possible.</p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <div class="notification-icon">
                                                            <i class="icon nalika-cloud" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="notification-content">
                                                            <span class="notification-date">16 Sept</span>
                                                            <h2>Sulaiman din</h2>
                                                            <p>Please done this project as soon possible.</p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <div class="notification-icon">
                                                            <i class="icon nalika-folder" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="notification-content">
                                                            <span class="notification-date">16 Sept</span>
                                                            <h2>Victor Jara</h2>
                                                            <p>Please done this project as soon possible.</p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <div class="notification-icon">
                                                            <i class="icon nalika-bar-chart" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="notification-content">
                                                            <span class="notification-date">16 Sept</span>
                                                            <h2>Victor Jara</h2>
                                                            <p>Please done this project as soon possible.</p>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="notification-view">
                                                <a href="#">View All Notification</a>
                                            </div>
                                        </div>
                                    </li> -->
                                    <!-- <li class="nav-item">
                                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                          <i class="icon nalika-user nalika-user-rounded header-riht-inf" aria-hidden="true"></i>
                                          <span class="admin-name">Advanda Cro</span>
                                          <i class="icon nalika-down-arrow nalika-angle-dw nalika-icon"></i>
                                        </a>
                                        <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                            <li><a href="#"><span class="icon nalika-user author-log-ic"></span> My Profile</a>
                                            </li>
                                            <li><a href="login.html"><span class="icon nalika-unlocked author-log-ic"></span> Log Out</a>
                                            </li>
                                        </ul>
                                    </li> -->
                                    <li class="nav-item dropdown">
                                      <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-mail nalika-chat-pro" aria-hidden="true"></i><span class="indicator-ms"></span></a>
                                      <div role="menu" class="author-message-top dropdown-menu animated zoomIn">
                                          <div class="message-single-top">
                                              <h1>Message</h1>
                                          </div>
                                          <ul class="message-menu">
                                              <li>
                                                  <a href="#">
                                                      <div class="message-img">
                                                          <img src="{{ asset('weighbridge_assets') }}/img/contact/1.jpg" alt="">
                                                      </div>
                                                      <div class="message-content">
                                                          <span class="message-date">16 Sept</span>
                                                          <h2>Advanda Cro</h2>
                                                          <p>Please done this project as soon possible.</p>
                                                      </div>
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="#">
                                                      <div class="message-img">
                                                          <img src="{{ asset('weighbridge_assets') }}/img/contact/4.jpg" alt="">
                                                      </div>
                                                      <div class="message-content">
                                                          <span class="message-date">16 Sept</span>
                                                          <h2>Sulaiman din</h2>
                                                          <p>Please done this project as soon possible.</p>
                                                      </div>
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="#">
                                                      <div class="message-img">
                                                          <img src="{{ asset('weighbridge_assets') }}/img/contact/3.jpg" alt="">
                                                      </div>
                                                      <div class="message-content">
                                                          <span class="message-date">16 Sept</span>
                                                          <h2>Victor Jara</h2>
                                                          <p>Please done this project as soon possible.</p>
                                                      </div>
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="#">
                                                      <div class="message-img">
                                                          <img src="{{ asset('weighbridge_assets') }}/img/contact/2.jpg" alt="">
                                                      </div>
                                                      <div class="message-content">
                                                          <span class="message-date">16 Sept</span>
                                                          <h2>Victor Jara</h2>
                                                          <p>Please done this project as soon possible.</p>
                                                      </div>
                                                  </a>
                                              </li>
                                          </ul>
                                          <div class="message-view">
                                              <a href="#">View All Messages</a>
                                          </div>
                                      </div>
                                    </li>
                                    <li class="nav-item nav-setting-open"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="icon nalika-menu-task"></i> <span>Waiting List</span></a>

                                        <div role="menu" class="admintab-wrap menu-setting-wrap menu-setting-wrap-bg dropdown-menu animated zoomIn">
                                            <ul class="nav nav-tabs custon-set-tab">
                                                <li class="active"><a data-toggle="tab" href="#Notes">Outward Waiting List</a>
                                                </li>
                                                <li><a data-toggle="tab" href="#Projects">inward Waiting List</a>
                                                </li>
                                                
                                            </ul>

                                            <div class="tab-content custom-bdr-nt">
                                                <div id="Notes" class="tab-pane fade in active">
                                                    <div class="notes-area-wrap">
                                                        <div class="note-heading-indicate">
                                                            <h2><i class="icon nalika-chat"></i> Outward Waiting List Of Vehicle</h2>
                                                            <p>All Vehicles In Waiting.</p>
                                                        </div>
                                                        <div class="notes-list-area notes-menu-scrollbar">
                                                            <ul class="notes-menu-list" id="outward-list">
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="Projects" class="tab-pane fade ">
                                                  <div class="notes-area-wrap">
                                                      <div class="note-heading-indicate">
                                                          <h2><i class="icon nalika-chat"></i> Inward Waiting List Of Vehicle</h2>
                                                          <p>All Vehicles in Waiting.</p>
                                                      </div>
                                                      <div class="notes-list-area notes-menu-scrollbar">
                                                          <ul class="notes-menu-list" id="inward-list">
                                                              
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div>
                                                
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                      </div>
                    </div>
                    </div>
                </div>
              </div>
            </div>
            <!-- Mobile Menu start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mobile-menu">
                                <nav id="dropdown">
                                    <ul class="mobile-menu-nav">
                                        <li><a data-toggle="collapse" data-target="#Charts" href="#">Home <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul class="collapse dropdown-header-top">
                                                <li><a href="index.html">Dashboard v.1</a></li>
                                                <li><a href="index-1.html">Dashboard v.2</a></li>
                                                <li><a href="index-3.html">Dashboard v.3</a></li>
                                                <li><a href="product-list.html">Product List</a></li>
                                                <li><a href="product-edit.html">Product Edit</a></li>
                                                <li><a href="product-detail.html">Product Detail</a></li>
                                                <li><a href="product-cart.html">Product Cart</a></li>
                                                <li><a href="product-payment.html">Product Payment</a></li>
                                                <li><a href="analytics.html">Analytics</a></li>
                                                <li><a href="widgets.html">Widgets</a></li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#demo" href="#">Mailbox <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="demo" class="collapse dropdown-header-top">
                                                <li><a href="mailbox.html">Inbox</a>
                                                </li>
                                                <li><a href="mailbox-view.html">View Mail</a>
                                                </li>
                                                <li><a href="mailbox-compose.html">Compose Mail</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#others" href="#">Miscellaneous <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="others" class="collapse dropdown-header-top">
                                                <li><a href="file-manager.html">File Manager</a></li>
                                                <li><a href="contacts.html">Contacts Client</a></li>
                                                <li><a href="projects.html">Project</a></li>
                                                <li><a href="project-details.html">Project Details</a></li>
                                                <li><a href="blog.html">Blog</a></li>
                                                <li><a href="blog-details.html">Blog Details</a></li>
                                                <li><a href="404.html">404 Page</a></li>
                                                <li><a href="500.html">500 Page</a></li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">Interface <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                                <li><a href="google-map.html">Google Map</a>
                                                </li>
                                                <li><a href="data-maps.html">Data Maps</a>
                                                </li>
                                                <li><a href="pdf-viewer.html">Pdf Viewer</a>
                                                </li>
                                                <li><a href="x-editable.html">X-Editable</a>
                                                </li>
                                                <li><a href="code-editor.html">Code Editor</a>
                                                </li>
                                                <li><a href="tree-view.html">Tree View</a>
                                                </li>
                                                <li><a href="preloader.html">Preloader</a>
                                                </li>
                                                <li><a href="images-cropper.html">Images Cropper</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#Chartsmob" href="#">Charts <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="Chartsmob" class="collapse dropdown-header-top">
                                                <li><a href="bar-charts.html">Bar Charts</a>
                                                </li>
                                                <li><a href="line-charts.html">Line Charts</a>
                                                </li>
                                                <li><a href="area-charts.html">Area Charts</a>
                                                </li>
                                                <li><a href="rounded-chart.html">Rounded Charts</a>
                                                </li>
                                                <li><a href="c3.html">C3 Charts</a>
                                                </li>
                                                <li><a href="sparkline.html">Sparkline Charts</a>
                                                </li>
                                                <li><a href="peity.html">Peity Charts</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#Tablesmob" href="#">Tables <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="Tablesmob" class="collapse dropdown-header-top">
                                                <li><a href="static-table.html">Static Table</a>
                                                </li>
                                                <li><a href="data-table.html">Data Table</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#formsmob" href="#">Forms <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="formsmob" class="collapse dropdown-header-top">
                                                <li><a href="basic-form-element.html">Basic Form Elements</a>
                                                </li>
                                                <li><a href="advance-form-element.html">Advanced Form Elements</a>
                                                </li>
                                                <li><a href="password-meter.html">Password Meter</a>
                                                </li>
                                                <li><a href="multi-upload.html">Multi Upload</a>
                                                </li>
                                                <li><a href="tinymc.html">Text Editor</a>
                                                </li>
                                                <li><a href="dual-list-box.html">Dual List Box</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#Appviewsmob" href="#">App views <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="Appviewsmob" class="collapse dropdown-header-top">
                                                <li><a href="basic-form-element.html">Basic Form Elements</a>
                                                </li>
                                                <li><a href="advance-form-element.html">Advanced Form Elements</a>
                                                </li>
                                                <li><a href="password-meter.html">Password Meter</a>
                                                </li>
                                                <li><a href="multi-upload.html">Multi Upload</a>
                                                </li>
                                                <li><a href="tinymc.html">Text Editor</a>
                                                </li>
                                                <li><a href="dual-list-box.html">Dual List Box</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages <span class="admin-project-icon nalika-icon nalika-down-arrow"></span></a>
                                            <ul id="Pagemob" class="collapse dropdown-header-top">
                                                <li><a href="login.html">Login</a>
                                                </li>
                                                <li><a href="register.html">Register</a>
                                                </li>
                                                <li><a href="lock.html">Lock</a>
                                                </li>
                                                <li><a href="password-recovery.html">Password Recovery</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu end -->
        
            <div class="breadcome-area">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="breadcome-list">
                          <div class="row">
                              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    
                                    <div class="breadcomb-ctn">
                                      <h1 style="color: white;">All Inward</h1>
                                    </div>
                                  
                                </div>
                          </div>
                          
                      </div>
                  </div>
                </div>
              </div>
            </div>
            
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label style="color: white;">Items</label>
                                        <select class="form-control" name="item_id" id="item_id" style="border-color: white;">
                                            <option value="">Select item</option>
                                            @foreach($items AS $item)
                                                <option value="{{ $item->hashid }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label style="color: white;">From Date</label>
                                        <input type="date" class="form-control" name="from_date" id="from_date" style="border-color: white;">
                                    </div>
                                    <div class="col-md-4">
                                        <label style="color: white;">To Date</label>
                                        <input type="date" class="form-control" name="to_date" id="to_date" style="border-color: white;">
                                    </div>
                                </div><br />
                                <input type="submit" class="btn btn-primary float-right mt-2" style="border-color: white;">
                                
                            </form>
                        </div>
                    </div>
                </div>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="header-title" style="color: white;">Inward Details</h4>
                            </div>
                            <p class="sub-header" style="color: white;">Following is the list of all the Inward Detail.</p>
                            <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable" style="border-color: white;">
                                <thead style="border-color: white;">
                                    <tr >
                                        <th style="color: white;">Id.No</th>
                                        <th style="color: white;">Date</th>
                                        <th style="color: white;">GP.No</th>
                                        <th style="color: white;">Vehicle No</th>
                                        <th style="color: white;"> Account Name </th>
                                        <th style="color: white;"> Item Name </th>
                                        <th style="color: white;"> Posted Weight </th>
                                        <th style="color: white;"> Company Weight </th>
                                        <th style="color: white;"> Fare </th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inward AS $inw)
                                    <tr>
                                    <td style="color: white;">{{ $loop->iteration }}</td>
                                    <td style="color: white;">{{ date('d-m-Y', strtotime($inw->date)) }}</td>
                                    <td style="color: white;">{{ $inw->gp_no }}</td>
                                    <td style="color: white;">{{ $inw->vehicle_no }}</td>
                                    <td style="color: white;">{{ $inw->account->name }}</td>
                                    <td style="color: white;">{{ $inw->item->name }}</td>
                                    <td style="color: white;">{{ $inw->posted_weight }}</td>
                                    <td style="color: white;">{{ $inw->company_weight }}</td>
                                    <td style="color: white;">{{ $inw->fare }}</td>
                                    
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
       
        
    </div>
    
    <!-- jquery
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/jquery-price-slider.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/jquery.meanmenu.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/owl.carousel.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/jquery.scrollUp.min.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/scrollbar/mCustomScrollbar-active.js"></script>
    <!-- metisMenu JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/metisMenu/metisMenu.min.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/metisMenu/metisMenu-active.js"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/sparkline/jquery.sparkline.min.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/sparkline/jquery.charts-sparkline.js"></script>
    <!-- calendar JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/calendar/moment.min.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/calendar/fullcalendar.min.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/calendar/fullcalendar-active.js"></script>
	<!-- float JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/flot/jquery.flot.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/flot/jquery.flot.resize.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/flot/curvedLines.js"></script>
    <script src="{{ asset('weighbridge_assets') }}/js/flot/flot-active.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="{{ asset('weighbridge_assets') }}/js/plugins.js"></script>
    <!-- main JS
		============================================ -->

    <script src="{{ asset('weighbridge_assets') }}/js/main.js"></script>
    <style>
    .dataTables_wrapper>.row {
        justify-content: center;
        align-items: center;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 10px;
    }

    .dt_table tr td,
    .dt_table tr th {
        vertical-align: middle;
    }

    .dt_table .table-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
</style>
<script src="{{ get_asset('admin_assets') }}/js/dataTable_bundled.min.js"></script>
{{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>  --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
{{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script> --}}

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

      var socket = io();
      socket.on("message", function (data) {
        document.getElementById("weight").innerHTML = data;
      });
      var weight = document.getElementById("weight").innerHTML;
      
      function addtareweight(){
        
        var weight = document.getElementById("weight").innerHTML;
        let tare = $("#tare_weight").val(weight);
        let weight_btn = $("#weight_btn").hide();

        
      }
    </script>
    <!-- Select 2 -->
    <script>
      $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    </script>
    <!-- All Request-->
    <script>
      $(document).ready(function(){

          //get All Inward Waiting List
          $.ajax({
              type: 'GET',
              datatype: 'JSON',
              contentType: 'application/json',
              url: "http://localhost/hyderabad_feed/public/outward/all_outward",
              success: function(result){
                console.log(result);
                
                $.each(result, function (i, value) {
                 account_name = value.account['name'];
                 const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                 const d = new Date(value.created_at);
                 let day = days[d.getDay()];

                  function DisplayCurrentTime() {
                    var date = new Date(value.created_at);
                    var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
                    var am_pm = date.getHours() >= 12 ? "PM" : "AM";
                    hours = hours < 10 ? "0" + hours : hours;
                    var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                    var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
                    return time = hours + ":" + minutes + ":" + seconds + " " + am_pm;
                    
                  };
                $('#outward-list').append(
                  '<li>',
                      '<a href="#">',
                        '<div class="notes-list-flow">',
                          '<div class="notes-img">',
                              '<h4>'+ value.vehicle_no  +'</h4>',
                          '</div>',
                          '<div class="notes-content">',
                            '<span style="text:bold">'+ day + DisplayCurrentTime()  +' </span>',
                            '<div class="form-group">',
                              '<a href="http://localhost:3000/public/edit_outward.html?id='+value.id+'"><button type="button"  class="btn btn-info" >Second Weight</button></a>',
                            '</div>',
                          '</div>',
                      '</div>',
                      '</a>',
                  '</li> <br />');

                //$('#driver_status').append('<option id=' + JSON.stringify(value.id) + '>' + JSON.stringify(value.bilty_no) + '</option>');
                });
              },
              error: function(){
                  console.log("no response");
              }
          });

          //get All Inward Waiting List
          $.ajax({
              type: 'GET',
              datatype: 'JSON',
              contentType: 'application/json',
              url: "http://localhost/hyderabad_feed/public/inward/all_inward",
              success: function(result){
                
                $.each(result, function (i, value) {
                 account_name = value.account['name'];
                 const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                 const d = new Date(value.created_at);
                 let day = days[d.getDay()];
                 
                  function DisplayCurrentTime() {
                    var date = new Date(value.created_at);
                    var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
                    var am_pm = date.getHours() >= 12 ? "PM" : "AM";
                    hours = hours < 10 ? "0" + hours : hours;
                    var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                    var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
                    return time = hours + ":" + minutes + ":" + seconds + " " + am_pm;
                    
                  };
                $('#inward-list').append('<li>',
                                                                    '<a href="#">',
                                                                      '<div class="notes-list-flow">',
                                                                        '<div class="notes-img">',
                                                                            '<h4>'+ value.vehicle_no  +'</h4>',
                                                                        '</div>',
                                                                        '<div class="notes-content">',
                                                                          '<span style="text:bold">'+ day + DisplayCurrentTime()  +' </span>',
                                                                          '<div class="form-group">',
                                                                            '<a href="http://localhost:3000/public/edit_inward.html?id='+value.id+'"><button type="button"  class="btn btn-info" >Second Weight</button></a>',
                                                                          '</div>',
                                                                        '</div>',
                                                                    '</div>',
                                                                    '</a>',
                                                                '</li> <br />');

                //$('#driver_status').append('<option id=' + JSON.stringify(value.id) + '>' + JSON.stringify(value.bilty_no) + '</option>');
                });
              },
              error: function(){
                  console.log("no response");
              }
          });

          //get All items
          $.ajax({
              type: 'GET',
              datatype: 'JSON',
                    contentType: 'application/json',
              url: "http://localhost/hyderabad_feed/public/outward/all_items",
              success: function(result){
                
                $.each(result, function (i, value) {
                
                $('#item_name').append('<option value=' + value.id + '>' + value.name + '</option>');
              });
              },
              error: function(){
                  console.log("no response");
              }
          });

          //get Accounts
          $.ajax({
              type: 'GET',
              datatype: 'JSON',
                    contentType: 'application/json',
              url: "http://localhost/hyderabad_feed/public/outward/all_accounts",
              success: function(result){
                
                $.each(result, function (i, value) {
                
                $('#account_name').append('<option value=' + value.id + '>' + value.name + '</option>');
              });
              },
              error: function(){
                  console.log("no response");
              }
          });

          //get Account Id
          $.ajax({
              type: 'GET',
              datatype: 'JSON',
              contentType: 'application/json',
              url: "http://localhost/hyderabad_feed/public/outward/acc_id",
              success: function(result){
                
                console.log(result.id);
                var dateObj = new Date();
                var month = dateObj.getMonth() + 1; //months from 1-12
                var year = dateObj.getFullYear().toString().substr(-2);
                
                var gp_no = $("#gp_no").val(result.id + "/" + month + "-" +year) ;

                console.log(gp_no);

              },
              error: function(){
                  console.log("no response");
              }
          });
          
      });
    </script>

    <script>
      
      function saveWeight() {
        
        var form = "outward";
        var tare_weight = $("#tare_weight").val()
        //Party detail
        var date = $("#date").val() ;
        var bilty_no =  $("#bilty_no").val() ;
        var vehicle_no = $("#vehicle_no").val() ;
        var account_id =  $("#account_name").val() ;
        var sub_dealer_name =  $("#sub_dealer_name").val() ;
          
        var item_id =  $("#item_name").val() ;  
        var no_of_begs = $("#no_of_begs").val() ;
        var fare_value =  $("#fare_value").val() ;
        var gp_no =  $("#gp_no").val() ;
        var remarks =  $("#remarks").val() ;

        //drivers detail
        var driver_name =  $("#driver_name").val() ;  
        var driver_phone_no = $("#driver_phone_no").val() ;
        var driver_status =  $("#driver_status").val() ;

        if(tare_weight == "" && item_id == ""  && vehicle_no == "" && account_id == "" ){
          Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Your Have to provide Current Fields!',
                  
                })
        }else{
          
          $.ajax({
            type: 'GET',
            data: $('#formLike').serialize(),
            url: "http://localhost/hyderabad_feed/public/outward/save",
            success: function(result){
              Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: 'Your Data Inserted Successfully!',
                  
                })
            },
            error: function(){
                console.log("no response");
            }
          });
          setTimeout(function () {
              window.location.href = "http://localhost:3000/public/outward.html";
            }, 3000);
        }
          
      }
    </script>



<script>
    var dtable = $("table.dt_table").DataTable({
        scrollX: false,//!0,
        responsive: true,
        lengthMenu: [
            [50, 100, 200, -1],
            [50, 100, 200, "All"]
        ],
        "paging": false,
        "bInfo" : false,
        //"ordering": false,
        //"info": false,
        //"searching" : false,
        buttons: ["copy", "print", "pdf", "csv", 'excel'],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });
    dtable.buttons().container().prependTo(".dataTables_wrapper .col-md-6:eq(0)");
</script>
   


</body>

</html>