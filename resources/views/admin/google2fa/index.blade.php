@extends('layouts.admin_login')
@section('title','Login OTP')
@section('page','page-login-otp')
@section('content')
    <div class="card-body">
        <h4 class="card-title">Enter One time Code</h4>
        <form class="form-horizontal" method="POST" action="{{ route('admin.2fa') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="one_time_password" style="font-size: 0.85rem">Check your Google Authenticator for the one time code</label>
                <input id="one_time_password" type="number" class="form-control" name="one_time_password" required autofocus>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    Submit
                </button>
            </div>
        </form>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            {{csrf_field()}}
            <button type="submit" class="btn btn-link">Logout from current session</button>
        </form>
    </div>
@endsection