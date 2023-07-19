<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OnField | Login</title>
    @include('admin.layouts.styles')

</head>

<body class="cm-layout cm-login-page">
<input type="hidden" value="{{ URL::to('/') }}" id="appurl">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container">
                <div class="min-vh-100 row justify-content-center align-items-center">
                    <div class="card">
                        <!-- <div class="card-header">{{ __('Login') }}</div> -->

                        <div class="card-body text-center">
                            <span class="logo">OnField</span>
                            <h1>Welcome back !</h1>
                            <h2>Sign In</h2>
                            <p>Welcome Back, Please sign in to continue</p>
                            <form method="POST" id="loginFrm">
                                @csrf

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <img src="{{ asset('public/dist/img/smartphone.svg') }}" alt="mobile">
                                        </span>
                                    </div>
                                    <input id="email" type="text" class="form-control" name="mobile" placeholder="Enter Your Mobile Number" value="" autofocus>
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <img src="{{ asset('public/dist/img/password.svg') }}" alt="mobile">
                                        </span>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Enter Your Password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <a class="cm-forgot-password" href="#">Forgot password?</a>
                                <button type="submit" class="btn btn-primary cm-login-btn submit-btn">
                                    {{ __('Login') }}
                                </button>
                                <span class="btn btn-primary cm-login-btn loading-btn">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->
    @include('admin.layouts.script')
    <script src="{{ asset('public/dist/js/pages/backoffice/login.js') }}"></script>
</body>

</html>