@extends('layouts.admin')
@section('title')@lang('Dashboard')@endsection
@section('page','page-settings')
@section('content')
    <div class="container my-2">
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
        <form action="{{route("admin.settings.update")}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="page-main-actions mb-4">
                <div class="page-actions">
                    <div class="d-md-flex align-items-center justify-content-between">
                        <div class="h3">Settings</div>
                        <div>
                            <button type="submit" class="btn btn-primary" href="{{route('admin.settings')}}">@lang("Save Settings")</button>
                        </div>
                    </div>
                </div>
            </div>
            <h5 class="mb-4">DVLA Settings</h5>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">@lang("Timezone")</label>
                        <div class="col-sm-6">
                            <select name="timezone" class="form-control">
                                <option value="Europe/London" {{$settings['timezone'] == 'Europe/London' ? 'selected' : ''}}>Europe/London</option>
                                <option value="America/New_York" {{$settings['timezone'] == 'America/New_York' ? 'selected' : ''}}>America/New_York</option>
                                <option value="Asia/Kolkata" {{$settings['timezone'] == 'Asia/Kolkata' ? 'selected' : ''}}>Asia/Kolkata</option>
                                <option value="UTC" {{$settings['timezone'] == 'UTC' ? 'selected' : ''}}>UTC</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="DVLALookup" class="col-sm-3 col-form-label">Enable DVLA Lookup</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="DVLALookup" name="DVLA_enabled">
                                <option value="1" {{$settings['dvla_enabled'] == 1 ? 'selected': ''}}>Enable</option>
                                <option value="0" {{$settings['dvla_enabled'] == 0 ? 'selected': ''}}>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dvla_api_key" class="col-sm-3 col-form-label">DVLA Access Token</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="dvla_api_key" type="text" name="dvla_api_key" value="{{$settings['dvla_api_key']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dvla_daily_limit" class="col-sm-3 col-form-label">DVLA Daily Limit</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="dvla_daily_limit" type="number" min="1" name="dvla_daily_limit" value="{{$settings['dvla_daily_limit']}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dvla_daily_usages" class="col-sm-3 col-form-label">Total DVLA lookups</label>
                        <div class="col-sm-6">
                            {{$settings['dvla_daily_usages']}}
                            <small id="dvla_daily_usages_help" class="form-text text-muted">
                                Total number of DVLA lookup done today. Resets to zero at midnight.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <h5 class="my-4">Autotrader Importer Settings</h5>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="autotrader_supplier_name" class="col-sm-3 col-form-label">Supplier Name</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="autotrader_supplier_name" type="text" name="autotrader_supplier_name" value="{{$settings['autotrader_supplier_name']}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="autotrader_client_id" class="col-sm-3 col-form-label">Client Id</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="autotrader_client_id" type="text" name="autotrader_client_id" value="{{$settings['autotrader_client_id']}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="autotrader_ftp_host" class="col-sm-3 col-form-label">FTP Host</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="autotrader_ftp_host" type="text" name="autotrader_ftp_host" value="{{$settings['autotrader_ftp_host']}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="autotrader_ftp_port" class="col-sm-3 col-form-label">FTP Port</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="autotrader_ftp_port" type="text" name="autotrader_ftp_port" value="{{$settings['autotrader_ftp_port']}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="autotrader_ftp_username" class="col-sm-3 col-form-label">FTP Username</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="autotrader_ftp_username" type="text" name="autotrader_ftp_username" value="{{$settings['autotrader_ftp_username']}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="autotrader_ftp_password" class="col-sm-3 col-form-label">FTP Password</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="autotrader_ftp_password" type="password" name="autotrader_ftp_password" value="{{$settings['autotrader_ftp_password']}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="enable_import_scheduler" class="col-sm-3 col-form-label">Enable Automatic Import</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="enable_import_scheduler" name="enable_import_scheduler">
                                <option value="1" {{$settings['enable_import_scheduler'] == 1 ? 'selected': ''}}>Enable</option>
                                <option value="0" {{$settings['enable_import_scheduler'] == 0 ? 'selected': ''}}>Disable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="import_scheduler" class="col-sm-3 col-form-label">Automatic Import Scheduler</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <input class="form-control" id="import_scheduler" type="time" name="import_scheduler[]" value="{{isset($import_schedulers[0]) ? $import_schedulers[0] : ''}}">
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <input class="form-control" id="import_scheduler" type="time" name="import_scheduler[]" value="{{isset($import_schedulers[1]) ? $import_schedulers[1] : ""}}">
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <input class="form-control" id="import_scheduler" type="time" name="import_scheduler[]" value="{{isset($import_schedulers[2])? $import_schedulers[2]: ''}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group row">
                        <label for="error_mail_to" class="col-sm-3 col-form-label">Send Error Email To</label>
                        <div class="col-sm-6">
                            <input class="form-control" id="error_mail_to" type="text" name="error_mail_to" value="{{$settings['error_mail_to']}}">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

