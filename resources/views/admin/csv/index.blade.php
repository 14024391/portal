@extends('layouts.admin')
@section('title','Autotrader Exporter')
@section('page','page-autotrader-exporter')
@section('content')
    <h4 class="text-center">Import Vehicle Data From CSV</h4>
    @if(session('success'))
        <div class="alert alert-info" role="alert">
            <div>{{session('success')}}</div>
        </div>
    @endif
    @if($errors->all())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $message)
                <p>{{$message}}</p>
            @endforeach
        </div>

    @endif

    <form action="{{route('admin.csv.import.process')}}" method="post">
        {{csrf_field()}}
        <button type="submit" class="btn btn-primary">Import from CSV</button>
    </form>
@endsection