@extends('layouts.admin')
@section('title')@lang('Home')@endsection
@section('content')
    <div class="my-4">
        <div class="card-box d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <div><i class="fas fa-exclamation-triangle text-danger" style="font-size: 5rem;"></i></div>
            <h2>{{ $exception->getMessage() }}</h2>
            <p>@lang("Please speak to Site Administration to get access rights.")</p>
        </div>
    </div>
@endsection
