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
<div id="app" class="@yield('page')">
    @auth
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container">
                <a  id="logo" class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('/images/logo.png') }}" alt="A&M Commercial Logo">
                </a>
                <div class="" id="navbarTopBar">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(Auth::user()->profile_url)
                                    <img src="{{asset('storage/'.Auth::user()->profile_url)}}" alt="{{Auth::user()->name}}" class="rounded-circle" style="max-width: 37px">
                                @else
                                    <img src="{{asset('images/profile_default.jpg')}}" alt="Please upload Profile image" class="rounded-circle" style="max-width: 37px">
                                @endif
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarMenu">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="//amcommercials.com" target="_blank"><i class="fas fa-home"></i></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{route('admin.vehicles.index')}}">Stock List <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown" id="vehicle-menu">
                            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Create an Advert
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <div class="container">
                                    <div class="d-flex-sm justify-content-center">
                                        <a class="dropdown-item vehicle-menu-item" href="{{url('admin/vehicle','truck')}}">
                                            <i class="vehicle-icon icon-truck"></i>
                                            <div>Truck</div>
                                        </a>
                                        <a class="dropdown-item vehicle-menu-item" href="{{url('admin/vehicle','plant')}}">
                                            <i class="vehicle-icon icon-plant"></i>
                                            <div>Plant</div>
                                        </a>
                                        <a class="dropdown-item vehicle-menu-item" href="{{url('admin/vehicle','farm')}}">
                                            <i class="vehicle-icon icon-farm"></i>
                                            <div>Farm</div>
                                        </a>
                                        <a class="dropdown-item vehicle-menu-item" href="{{url('admin/vehicle','car')}}">
                                            <i class="vehicle-icon icon-car"></i>
                                            <div>Car</div>
                                        </a>
                                        <a class="dropdown-item vehicle-menu-item" href="{{url('admin/vehicle','van')}}">
                                            <i class="vehicle-icon icon-van"></i>
                                            <div>Van</div>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </li>
                        @can('create',App\Models\AutotraderImport::class)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="importerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                AutoTrader
                            </a>
                            <div class="dropdown-menu" aria-labelledby="importerDropdown">
                                <a class="dropdown-item" href="{{route('admin.autotrader.importer.index')}}">Exporter</a>
                            </div>
                        </li>
                         @endcan
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        @can('view',App\Models\User::class)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Settings
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('view',App\Models\User::class)
                                    <a class="dropdown-item" href="{{route('admin.users')}}">Users</a>
                                @endcan
                                @can('view',App\Models\Permission::class)
                                    <a class="dropdown-item" href="{{route('admin.permissions')}}">Permissions</a>
                                @endcan
                                @can('update',App\Models\Setting::class)
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{route('admin.settings')}}">Settings</a>
                                @endcan
                            </div>
                        </li>
                        @endcan
                    </ul>

                </div>
            </div>
        </nav>
    @endauth
    @yield('stocklist')
    @yield('importer')
    <main class="py-4">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Scripts -->
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
<script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
