@extends('layouts.admin')
@section('title','Vehicle Stock')
@section('page','page-stocklist')
@section('stocklist')
    <div>
        <?php $message = session('status') ? session('status') : ''?>
        <stocklist-component
                :pvehicles="{{ json_encode($vehicles) }}"
                :images="{{ json_encode($images) }}"
                :stats="{{ json_encode($stats) }}"
                :message="{{ json_encode($message) }}"
        ></stocklist-component>
    </div>
@endsection