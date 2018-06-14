@extends('layouts.admin')
@section('title')@lang('User')@endsection
@section('content')
    <form action="{{route('admin.user.update')}}" method="POST" novalidate enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="form_action" value="save">
        <input type="hidden" name="id" value="{{$user->id}}">
        <div class="page-main-actions mb-4">
            <div class="page-actions">
                <div class="d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">User #{{$user->name}}</h3>
                    @if(!$user->google2fa_secret || $user->google2fa_secret == null)
                        <p class="text-danger">2 Factor Authentication is not set for this user</p>
                    @endif
                </div>
            </div>
        </div>
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
                <ul class="mb-0 pl-2">
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
                        <div class="col col-sm-12 col-md-4 col-lg-3">
                            <div class="nav flex-column nav-pills pb-5" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active mt-2" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Basic</a>
                                <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Password</a>
                                <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
                                <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-google" role="tab" aria-controls="v-pills-google" aria-selected="false">Google Authenticator</a>
                            </div>
                        </div>
                        <div class="col col-sm-12 col-md-8 col-lg-9">
                            <div class="tab-content" id="v-pills-tabContent" style="height: 100%">
                                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                    <div class="row">
                                        <div class="col col-md-12 col-lg-8">
                                            <h5 class="mb-4">@lang("Basic Information")</h5>
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-4 col-md-3 col-form-label">@lang("Name")</label>
                                                <div class="col-sm-9 required">
                                                    <input type="text" name="name" class="form-control" id="inputName" aria-describedby="nameHelp"  value="{{$user->name}}" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-4 col-md-3 col-form-label">@lang("Email")</label>
                                                <div class="col-sm-9 required">
                                                    <input type="email" name="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" value="{{$user->email}}" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="selectRole" class="col-sm-4 col-md-3 col-form-label">@lang("Role")</label>
                                                <div class="col-sm-9 required select">
                                                    <select class="custom-select" name="role" required>
                                                        <option selected>@lang("Select Role")</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{$role->id}}" {{ in_array($role->id , $user->roles->pluck('id')->toArray()) ? 'selected' : ''}}>{{$role->description}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-4 col-md-3 col-form-label">@lang("Status")</label>
                                                <div class="col-sm-9 required">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="active" class="form-check-input" {{$user->active ? 'checked': ''}} value="1">
                                                        @lang("Active")
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-4 col-md-3 col-form-label">@lang("Profile Photo")</label>
                                                <div class="col-sm-9">
                                                    <img src="{{asset('storage/'.$user->profile_url)}}" alt="{{$user->name}}" class="img-thumbnail rounded-circle" style="max-width: 240px;">
                                                    <input type="file" name="profile_image" class="form-control-file" value="{{old('profile_image')}}" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-4">
                                        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                                            @lang("Delete")
                                        </a>
                                        <button type="submit" class="btn btn-primary" href="{{route('admin.user.create')}}">@lang("Update User")</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <div class="row">
                                        <div class="col col-md-12 col-lg-8">
                                            <h5 class="mb-4">@lang("Change Password")</h5>
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
                                        </div>
                                    </div>
                                    <div class="my-4">
                                        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                                            @lang("Delete")
                                        </a>
                                        <button type="submit" class="btn btn-primary" href="{{route('admin.user.create')}}">@lang("Update User")</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="row">
                                        <div class="col col-md-12 col-lg-8">
                                            <h5 class="mb-4">@lang("Settings")</h5>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-md-3 col-form-label">@lang("Locale")</label>
                                                <div class="col-sm-9">
                                                    <select name="locale" class="form-control">
                                                        <option value="en" {{$user->locale == 'en' ? 'selected' : ''}}>English</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-md-3 col-form-label">@lang("Timezone")</label>
                                                <div class="col-sm-9">
                                                    <select name="timezone" class="form-control">
                                                        <option value="Europe/London" {{$user->timezone == 'Europe/London' ? 'selected' : ''}}>Europe/London</option>
                                                        <option value="America/New_York" {{$user->timezone == 'America/New_York' ? 'selected' : ''}}>America/New_York</option>
                                                        <option value="Asia/Kolkata" {{$user->timezone == 'Asia/Kolkata' ? 'selected' : ''}}>Asia/Kolkata</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-4">
                                        <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                                            @lang("Delete")
                                        </a>
                                        <button type="submit" class="btn btn-primary" href="{{route('admin.user.create')}}">@lang("Update User")</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-google" role="tabpanel" aria-labelledby="v-pills-google-tab">
                                    <div class="row">
                                        <div class="col col-md-8">
                                            <h5 class="mb-4">Update 2 Factor Authentication</h5>
                                            <p>Click the button to generate QR scan <a class="btn btn-sm btn-primary" href="{{route('admin.user.reauthenticate',$user->id)}}">Genrate</a>
                                            </p>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this User. ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form action="{{route("admin.user.destroy")}}" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <button type="submit" class="btn btn-primary">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection