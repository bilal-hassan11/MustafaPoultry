@extends('layouts.admin_auth')
@section('title', 'Login')
@section('page-heading', 'Enter your email address and password to access admin panel.')

@section('content')

<div class="row ">
    <center>
        <img src="{{ asset('new_assets') }}/images/main-logo.jpeg" style="flex-direction:center; width:330px; height:160px;"alt=""> 
    </center>

</div>
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
@endsection

@section('after-page')
<div class="row mt-3">
    <div class="col-12 text-center">
        @if (Route::has('password.request'))
        <a class="text-white-50 ml-1" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
        @endif
    </div> <!-- end col -->
</div>
@endsection