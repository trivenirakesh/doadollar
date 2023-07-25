@extends('layouts.auth')
@section('content')
<div>
    <h2 class="mt-4">{{ __('Reset Password') }}</h2>
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Your Email">
            @error('email')
            <span class="error" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary cm-login-btn">
            {{ __('Reset Password') }}
        </button>
    </form>
</div>
@endsection