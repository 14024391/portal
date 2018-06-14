@extends('layouts.admin')
@section('title')@lang('Permissions')@endsection
@section('content')
    <div>
        <admin-permissions
            :roles="{{ json_encode($roles) }}"
            :groups="{{ json_encode($groups) }}"
            :permissions="{{ json_encode($permissions) }}"
        ></admin-permissions>
    </div>
@endsection