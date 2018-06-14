@extends('layouts.admin_login')
@section('content')
    <div class="card-body">
        <h4 class="card-title">Login</h4>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group" style="position: relative">
                <label for="password">Password
                </label>
                <a class="btn btn-link forget-pass-link" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
                <div class="password-field">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                    <button type="button" class="btn btn-link pass-show-btn">Show</button>
                    <button type="button" class="btn btn-link pass-hide-btn" style="display: none">Hide</button>
                </div>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    Login
                </button>
            </div>
        </form>
    </div>
@endsection
