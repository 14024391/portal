@extends('layouts.admin')
@section('title','Add New Truck')
@section('page','page-add-vehicle')
@section('content')
    <div style="min-height: 600px">
        <div v-if="loading" class="text-center pt-5">
            Loading Components. Please wait ...
        </div>
        <vehicle-component
                :type="{{  json_encode($type) }}"
                :pvehicle="{{ json_encode($vehicle) }}"
                :pvehicledata = "{{ json_encode($vehicleData) }}"
                :loading.sync="loading"
        ></vehicle-component>
    </div>
@endsection