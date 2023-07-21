<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    @include('admin.layouts.styles')
</head>

<body class="cm-layout cm-login-page">
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="container">
                <div class="min-vh-100 row justify-content-center align-items-center">
                    <div class="login-box text-center">
                        <img src="{{ asset('public/dist/img/DoADollar.png') }}" class="w-50" alt="DoADollar">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>