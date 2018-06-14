@extends('layouts.admin')
@section('title')@lang('Roles')@endsection
@section('content')
    <form action="{{route('role.update',$role->id)}}" method="POST" novalidate>
        {{ csrf_field() }}
        <div class="page-main-actions">
            <div class="page-actions">
                <div class="d-md-flex align-items-center justify-content-between">
                    <div class="page-title">@lang("Update Role")</div>
                    <div>
                        <a href="{{URL::previous()}}" class="btn btn-link btn-icon btn-back">@lang("Back") <i class="material-icons">{{$ltr ? 'arrow_back' : 'arrow_forward'}}</i></a>
                        <a href="{{route('role.create')}}"  class="btn btn-link">@lang("Add New Role")</a>
                        @if(!in_array($role->name,config('constant.roles')))
                        <a href="#" data-toggle="modal" data-target="#deleteModal" class="btn btn-link">@lang("Delete Role")</a>
                        <button type="submit" class="btn btn-primary">@lang("Update Role")</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col col-md-3">
                                <div class="nav flex-column nav-pills pb-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <div class="nav-title">@lang("Roles")</div>
                                    @foreach($roles as $r)
                                        <a class="nav-link mt-2 {{request('id') == $r->id ? 'active': ''}}" href="{{route('role',$r->id)}}">{{$r->description}}</a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col col-md-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <div class="row">
                                            <div class="col col-md-8">
                                                <h5 class="mb-4">@lang("Role Detail")</h5>
                                                <div class="form-group row">
                                                    <label for="inputName" class="col-sm-4 col-md-3 col-form-label">@lang("Name")</label>
                                                    <div class="col-sm-9 required">
                                                        <input type="text" name="name" class="form-control" value="{{$role->name}}" required>
                                                        <small id="emailHelp" class="form-text text-muted">@lang("Lowercase , only contains letter")</small>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail" class="col-sm-4 col-md-3 col-form-label">@lang("Description")</label>
                                                    <div class="col-sm-9 required">
                                                        <input type="text" name="description" class="form-control" value="{{$role->description}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Selected Role will be deleted. Are you sure ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form method="POST" action="{{route('role.destroy',$role->id)}}">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$role->id}}">
                        <button type="submit" class="btn btn-orange">Delete Role</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection