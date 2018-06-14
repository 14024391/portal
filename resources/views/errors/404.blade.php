@extends('layouts.admin')
@section('title')@lang('Home')@endsection
@section('content')
    <div class="container my-4">
        <div class="row">
            <div class="col">
                <h2>{{ $exception->getMessage() }}</h2>
            </div>
        </div>
    </div>
@endsection
