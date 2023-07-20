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

                    <!-- <div class="card-header">{{ __('Login') }}</div> -->

                    <div class="login-box text-center">
                        <img src="{{ asset('public/dist/img/DoADollar.png') }}" class="w-50" alt="DoADollar">
                        <h2 class="mt-4">Log in</h2>
                        <p>Welcome Back, Please sign in to continue</p>
                        <form method="POST" id="loginFrm">
                            @csrf

                            <div class="form-group">
                                <input id="email" type="text" class="form-control" name="username"
                                    placeholder="User Name" value="" autofocus>
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input id="password" type="password" class="form-control" name="password"
                                    placeholder="Enter Your Password">

                                @error('Password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="RememberMe">
                                    <label class="custom-control-label font-weight-normal" for="RememberMe">Remember me</label>
                                </div>
                                <a class="color-dark" href="#">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary cm-login-btn">
                                {{ __('Login') }}
                            </button>
                        </form>
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