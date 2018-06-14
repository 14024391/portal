@extends('layouts.admin')
@section('title')@lang('Create User')@endsection
@section('content')
    <form action="{{route('admin.user.register')}}" method="POST" novalidate enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="form_action" value="create">
        <div class="h2 text-center">Create A New User</div>
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
            <div class="row justify-content-center mt-4">
                <div class="card-box col col-md-6">
                    <h4 class="form-title mb-4">Account Information</h4>
                    <div class="form-group row my-4">
                        <label for="inputEmail" class="col-sm-4 col-md-3 col-form-label">@lang("Profile Photo")</label>
                        <div class="col-sm-9 text-center">
                            <img src="{{asset('images/profile_default.jpg')}}" alt="upload photo" class="img-thumbnail rounded-circle" style="max-width: 200px;">
                            <input type="file" name="profile_image" class="form-control-file" value="{{old('profile_image')}}" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-md-3 col-form-label">@lang("Name")</label>
                        <div class="col-sm-9 required">
                            <input type="text" name="name" class="form-control" id="inputName" aria-describedby="nameHelp" value="{{old('name')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-4 col-md-3 col-form-label">@lang("Email")</label>
                        <div class="col-sm-9 required">
                            <input type="email" name="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" value="{{old('email')}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-md-3 col-form-label">@lang("Password")</label>
                        <div class="col-sm-9 required">
                            <input type="password" name="password" class="form-control" id="inputPassword" aria-describedby="passwordHelp" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputConfirmPassword" class="col-sm-4 col-md-3 col-form-label">@lang("Confirm Password")</label>
                        <div class="col-sm-9 required">
                            <input type="password" name="password_confirmation" class="form-control" id="inputConfirmPassword" aria-describedby="passwordHelp" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="selectRole" class="col-sm-4 col-md-3 col-form-label">@lang("Role")</label>
                        <div class="col-sm-9 required select">
                            <select class="custom-select" name="role" required>
                                <option selected>@lang("Select Role")</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}" {{old('role') == $role->id ? 'selected' : ''}}>{{$role->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="active" class="form-check-input" checked value="1">
                            @lang("Active")
                        </label>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">@lang("Save User")</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection