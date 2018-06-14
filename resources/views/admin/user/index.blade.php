@extends('layouts.admin')
@section('title')@lang('Users')@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex mb-4">
                <h3>Users</h3>
                <a class="btn btn-primary ml-auto" href="{{route('admin.user.create')}}">Add New User</a>
            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form method="GET" action="{{route('admin.users')}}">
                <div class="d-lg-flex mb-4">
                    <div class="mb-2">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <label class="sr-only" for="inlineFormInput">@lang("Name")</label>
                                <input type="text" name="name" class="form-control mb-2 mb-sm-0" id="inlineFormInput" placeholder="@lang("Name")" value="{{request('name')}}">
                            </div>
                            <div class="col-auto">
                                <label class="sr-only" for="inlineFormInputGroup">@lang("Email")</label>
                                <input type="text" name="email" class="form-control " id="inlineFormInputGroup" placeholder="@lang("Email")" value="{{request('email')}}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-info">
                                    Apply Filter
                                </button>
                            </div>
                            <a class="btn btn-link" href="{{url('admin/users')}}">@lang("Reset")</a>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <div class="d-inline-flex align-items-center">
                            <span class="px-2" style="width:110px">Sort By</span>
                            <select class="custom-select" name="sort_by" onchange="this.form.submit()">
                                <option value="name" {{request('sort_by') == 'name'? 'selected': ''}}>@lang("Name")</option>
                                <option value="email" {{request('sort_by') == 'email'? 'selected': ''}}>@lang("Email")</option>
                                <option value="id" {{request('sort_by') == 'id'? 'selected': ''}}>@lang("Created At")</option>
                                <option value="active" {{request('sort_by') == 'active' ? 'selected': ''}}>@lang("Active")</option>
                            </select>

                            <div class="order-by">
                                @if(request('order_by') == 'dsc')
                                    <div class="form-check" data-toggle="tooltip" title="High to Low">
                                        <label class="form-check-label">
                                            <input class="form-check-input d-none" type="radio" name="order_by" onclick="this.form.submit()" value="asc" {{request('order_by') == 'asc'? 'checked': ''}}>
                                            <i class="fas fa-arrow-down fa-lg"></i>
                                        </label>
                                    </div>
                                @else
                                    <div class="form-check"  data-toggle="tooltip" title="Low to High">
                                        <label class="form-check-label">
                                            <input class="form-check-input d-none" type="radio" name="order_by" onclick="this.form.submit()" value="dsc" {{request('order_by') == 'dsc'? 'checked': ''}}>
                                            <i class="fas fa-arrow-up fa-lg"></i>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-box">
        <table class="table table-hover table-sm table-responsive-md">
            <thead class="th-bg-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">@lang("Name")</th>
                <th scope="col">@lang("Email")</th>
                <th scope="col">@lang("Roles")</th>
                <th scope="col">@lang("Active")</th>
                <th scope="col">@lang("2 Factor Login")</th>
                <th scope="col">@lang("Updated At")</th>
                <th scope="col">@lang("Action")</th>
            </tr>
            </thead>
            <tbody class="text-secondary">
            @if($users)
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            @foreach($user->roles as $role)
                                {{$role->description}}
                            @endforeach
                        </td>
                        <td class="text-center">{{$user->active ? 'Yes' : 'No'}}</td>
                        <td class="text-center">{{$user->google2fa_secret ? 'Yes' : 'No'}}</td>
                        <td>{{$user->updated_at->format('d-m-Y H:i')}}</td>
                        <td><a href="{{route('admin.user',$user->id)}}" class="link"><i class="fas fa-edit    fa-1x">edit</i></a></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <div class="d-md-flex align-items-center pt-4">
            <div class="">
                @lang("showing") {{$users->count()}} @lang("of") {{$users->total()}} @lang("users")
            </div>
            <div class="mr-auto">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection