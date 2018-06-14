@extends('layouts.admin')
@section('title','Autotrader Exporter')
@section('page','page-autotrader-exporter')
@section('content')
    <h4 class="text-center">Import Vehicle Data From Autotrader</h4>
    @if(session('success'))
        <h3>{{session('success')}}</h3>
    @endif
    <form action="{{route('admin.autotrader.exporter.process')}}" method="post">
        {{csrf_field()}}
        <button type="submit" class="btn btn-primary">Process Records</button>
    </form>
@endsection