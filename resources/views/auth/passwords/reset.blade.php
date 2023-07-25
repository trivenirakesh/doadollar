@extends('layouts.auth')
@section('content')
<div>

    <h2 class="mt-4"> {{ __('Reset Password') }}</h2>
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="POST" id="loginFrm" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Enter Your Email">
            @error('email')
            <span class="error" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" required value="{{ old('password') }}" autocomplete="new-password" name="password" placeholder="Enter Your Password">
            @error('password')
            <span class="error" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control @error('email') is-invalid @enderror" required autocomplete="new-password" name="password_confirmation" placeholder="Confirm Password">
        </div>

        <button type="submit" class="btn btn-primary cm-login-btn">
            {{ __('Reset Password') }}
        </button>
    </form>
</div>
@endsection