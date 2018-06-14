@extends('layouts.admin')
@section('title')@lang('Create User')@endsection
@section('content')
    <div class="text-center">
        <h3 class="panel-heading">Set up Google Authenticator</h3>
        <p>Set up your two factor authentication by scanning the barcode below.</p>
        <p>Alternatively, you can use the code {{ $secret }}</p>
        <div>
            <img src="{{ $QR_Image }}">
        </div>
        @if (!@$reauthenticating)
            <p>You must set up your Google Authenticator app before continuing. You will be unable to login otherwise</p>
            <div>
                <a href="{{route('admin.user.register.complete')}}"><button class="btn-primary">Complete Registration</button></a>
            </div>
        @endif
    </div>
@endsection