@extends('layouts.admin')
@section('title')@lang('Roles')@endsection
@section('content')
    <form action="{{route('role.store')}}" method="POST" novalidate>
        {{ csrf_field() }}
        <div class="page-main-actions">
            <div class="page-actions">
                <div class="d-md-flex align-items-center justify-content-between">
                    <div class="page-title">@lang("Create Role")</div>
                    <div>
                        <a href="{{route("role",1)}}" class="btn btn-link btn-icon btn-back"><i class="material-icons">{{$ltr ? 'arrow_back' : 'arrow_forward'}}</i> @lang("Back")</a>
                        <button type="submit" class="btn btn-primary">@lang("Save Role")</button>
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

            <div class="card-box">
                <div class="row">
                    <div class="col col-md-3">
                        <div class="nav flex-column nav-pills pb-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <div class="nav-title">@lang("Existing Roles")</div>
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
                                        <h5 class="mb-4">@lang("Create New Role")</h5>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-4 col-md-3 col-form-label">@lang("Name")</label>
                                            <div class="col-sm-9 required">
                                                <input type="text" name="name" class="form-control" value="{{old('name')}}" required>
                                                <small id="emailHelp" class="form-text text-muted">@lang("Lowercase , only contains letter")</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-4 col-md-3 col-form-label">@lang("Description")</label>
                                            <div class="col-sm-9 required">
                                                <input type="text" name="description" class="form-control" value="{{old('description')}}" required>
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

@endsection