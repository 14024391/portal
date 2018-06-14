@extends('layouts.admin')
@section('title','Autotrader Importer')
@section('page','page-autotrader-importer')
@section('importer')
    <div id="export-filters">
        <div class="container">
            <form action="{{route('admin.autotrader.importer.create')}}" method="post">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-12 col-md-8">
                        <label class="filter-label">Stock Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="instock" value="In Stock" name="stockStatus[]" checked>
                            <label class="form-check-label" for="instock">In Stock</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="sold" value="Sold" name="stockStatus[]">
                            <label class="form-check-label" for="sold">Sold</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="duein" value="Due In" name="stockStatus[]">
                            <label class="form-check-label" for="duein">Due In</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="Reserved" value="Reserved" name="stockStatus[]">
                            <label class="form-check-label" for="Reserved">Reserved</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mr-auto text-right">
                        <button type="submit" class="btn btn-primary">Create New Import File</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-8">
                        <label class="filter-label">Advert Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="advertised" :value="1" name="advertStatus[]" checked>
                            <label class="form-check-label" for="advertised">Advertised</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="notadvertised" :value="0" name="advertStatus[]">
                            <label class="form-check-label" for="notadvertised">Not Advertised</label>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="container mt-4">
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session('status')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{session('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
         @endif
        @if ($errors->any())
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                 </ul>
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
         @endif
    </div>
@endsection
@section('content')
    <div>
        <table class="table table-striped table-sm">
            <thead>
                <tr class="text-center">
                    <th class="text-left">#</th>
                    <th class="text-left">File Name</th>
                    <th>Records</th>
                    <th>Created At</th>
                    <th>Last Updated At</th>
                    <th>Processed At</th>
                    <th>Download</th>
                    <th>Upload</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @if($imports->count() == 0)
                    <tr><td colspan="9">No Records Found</td></tr>
                @endif
                @foreach($imports as $import)
                    <tr>
                        <td>{{$import->id}}</td>
                        <td>{{$import->file_name}}</td>
                        <td class="text-center">{{$import->records}}</td>
                        <td class="text-center">{{$import->created_at}}</td>
                        <td class="text-center">{{$import->updated_at}}</td>
                        <td class="text-center">{{$import->processed_at ? $import->processed_at: 'Not Processed'}}</td>
                        <td class="text-center"><a href="{{route('admin.autotrader.importer.download',$import->id)}}"><i class="fas fa-download"></i></a></td>
                        <td class="text-center">
                            @if($import->processed_at)
                                Uploaded
                            @else
                                <form action="{{route('admin.autotrader.importer.process',$import->id)}}" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="import_id" value="{{$import->id}}">
                                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                </form>
                            @endif
                        </td>
                        <td class="text-center"><a href="{{route('admin.autotrader.importer.delete',$import->id)}}"><i class="far fa-trash-alt text-danger"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection