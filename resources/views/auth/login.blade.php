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
    <link rel="shortcut icon" href="{{ asset('new_assets') }}/images/main-logo.png">
    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('new_assets') }}/css/vendors_css.css">
        
    <!-- Style-->  
    <link rel="stylesheet" href="{{ asset('new_assets') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('new_assets') }}/css/skin_color.css">
    <!-- App css -->
    <link href="{{ asset('admin_assets') }}/css/bundled.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets') }}/css/dianujAdminCss.css" rel="stylesheet" type="text/css" />
    <link href="//fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

</head>
	
<body class="hold-transition theme-primary bg-img" style="background-image: url(http://poultrytrading1.com/FeedSystem22/public/new_assets/images/auth-bg/bg-16.jpg)">
	
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">	
			
			<div class="col-12">
				<div class="row justify-content-center g-0">
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<h2 class="text-primary fw-600"> Al Mustafa Poultry System</h2>
								<p class="mb-0 text-fade">Login in to continue to Al Mustafa Poultry .</p>							
							</div>
							<div class="text-center w-100 m-auto">
                                 <a href="{{route('admin.home')}}">
                                    <span><img src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg" alt="" width="300" height="300"></span>
                                </a>
                                
                            </div>
							<div class="p-40">
								<form method="POST" action="{{ route('login') }}" class="custom_form" novalidate>
									@csrf
									<div class="mb-3">
										<label for="username">{{ __('Username') }}</label>
										
										<input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autofocus placeholder="Enter your username">
										@error('username')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								
									<div class="mb-3">
										<label for="password">{{ __('Password') }}</label>
										<input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
										@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								
									@if ($errors->has('active'))
									<p class="alert alert-danger mt-2">
										<span class="help-block">
											<strong>{{ $errors->first('active') }}</strong>
										</span>
									</p>
									@endif
								
									<div class="mb-3">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" {{ old('remember') ? 'checked' : '' }}>
											<label class="form-check-label" for="checkbox-signin">{{ __('Remember Me') }}</label>
										</div>
									</div>
								
									<div class="text-center d-grid">
										<button class="btn btn-primary btn-block" type="submit"> {{ __('Login') }} </button>
									</div>
								
								</form>
								
								
								
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	


	<!-- Vendor JS -->
	<script src="{{ asset('admin_assets') }}/js/vendors.min.js"></script>
	<script src="{{ asset('admin_assets') }}/js/pages/chat-popup.js"></script>
	<script src="{{ asset('admin_assets') }}/icons/feather-icons/feather.min.js"></script>	
    <script src="{{ asset('admin_assets') }}/js/bundled.min.js"></script>
    <script src="{{ asset('admin_assets') }}/js/custom.js"></script>
	
    @yield('page-scripts')
</body>

<!-- Mirrored from lion-admin-templates.multipurposethemes.com/bs5/template/horizontal/main/auth_login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 23 Dec 2022 16:14:48 GMT -->
</html>
