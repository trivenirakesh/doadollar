@extends('layouts.auth')
@section('content')
<div>
    <h2 class="mt-4">Log in</h2>
    <p>Welcome Back, Please sign in to continue</p>
    
    
    <form method="POST" id="loginFrm" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="@if(!empty(request()->cookie('login_email'))) {{ request()->cookie('login_email') }}  @else {{ old('email') }} @endif" id="email" autocomplete="email" autofocus placeholder="Enter Your Email">
            <!-- @error('email')
            <span class="error" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror -->
        </div>

        <div class="form-group">
            <input id="password" type="password" class="form-control @error('email') is-invalid @enderror" value="@if(!empty(request()->cookie('login_password'))){{request()->cookie('login_password')}}@else{{ old('email')}}@endif" id="password" name="password" placeholder="Enter Your Password">
            <!-- @error('Password')
            <span class="error" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror -->
        </div>
        <div class="d-flex justify-content-between mb-4">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="RememberMe" name="remember" @if(!empty(request()->cookie('login_password'))) checked  @elseif(!empty(old('remember'))) checked @endif>
                <label class="custom-control-label font-weight-normal" for="RememberMe">{{ __('Remember Me') }}</label>
            </div>
            <a class="color-dark" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <div class="py-3 text-start">
            @if($errors->any())
            {!! implode('', $errors->all('<div><span class="error text-start" role="alert"><strong>:message</strong> </span></div>')) !!}
            @endif
        </div>

        <button type="submit" class="btn btn-primary cm-login-btn">
            {{ __('Login') }}
        </button>
    </form>
</div>

@endsection