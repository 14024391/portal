@extends('layouts.admin')
@section('title','Add New Truck')
@section('page','page-edit-vehicle')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
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



    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Vehicle.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form action="{{route('admin.vehicle.destroy')}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="vehicle_id" value="{{$id}}">
                        <input type="hidden" name="isAjax" value="0">
                        <button type="submit" class="btn btn-danger">Delete Vehicle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection