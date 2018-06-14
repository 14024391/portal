<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'A&M Commercials') }}</title>
    <link rel="icon" href="{{asset('favicon.png')}}" type="image/png" sizes="16x16">
    <!-- Styles -->
    <link href="{{ mix('css/admin.css') }}" rel="stylesheet">
</head>
<body>
<div id="login-page">
    <div id="login-wrapper" class="d-flex flex-column justify-content-center align-items-center">
        <div id="login-box">
            <div id="login-logo">
                <img src="{{asset('images/logo.png')}}" />
            </div>
            <div class="card">
                @yield('content')
            </div>
            <p class="copywrite">Copyright © 2018 — A&M Commercials</p>
        </div>
    </div>
</div>

<!-- Scripts -->
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<script src="{{ mix('js/admin.js') }}"></script>
<script>
    $(function(){
        $('.pass-show-btn').click(function () {
            $('#password').prop('type','text');
            $(this).hide(function () {
                $('.pass-hide-btn').show()
            })
        });
        $('.pass-hide-btn').click(function () {
            $('#password').prop('type','password');
            $(this).hide(function () {
                $('.pass-show-btn').show()
            })
        });
    })
</script>
</body>
</html>
