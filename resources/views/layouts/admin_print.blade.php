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
<div>
    @yield('content')
</div>
<!-- Scripts -->
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
