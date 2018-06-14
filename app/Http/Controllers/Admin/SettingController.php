<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Models\Make;
use App\Models\Setting;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Http\Resources\MakeResource;

class SettingController extends Controller
{
    public function index(){
        abort_if(! Auth::user()->hasRole('superadmin'), 403);
        $settings = Setting::pluck('value','name');

        $import_schedulers = json_decode($settings['import_scheduler']) ? json_decode($settings['import_scheduler']) : [];
        return view("admin.settings.settings",["settings" => $settings,'import_schedulers' => $import_schedulers]);
    }

    public function update(Request $request){
        abort_if(! Auth::user()->hasRole('superadmin'), 403);
        $request->validate([
            'timezone' => 'required',
            'dvla_api_key' => 'required',
            'autotrader_supplier_name' => 'required',
            'autotrader_client_id' => 'required',
        ]);
        try{
            Setting::where('name','locale')->update(['value' => $request->get('locale')]);
            Setting::where('name','timezone')->update(['value' => $request->get('timezone')]);
            Setting::where('name','dvla_api_key')->update(['value' => $request->get('dvla_api_key')]);
            Setting::where('name','dvla_daily_limit')->update(['value' => $request->get('dvla_daily_limit')]);

            Setting::where('name','autotrader_supplier_name')->update(['value' => $request->get('autotrader_supplier_name')]);
            Setting::where('name','autotrader_client_id')->update(['value' => $request->get('autotrader_client_id')]);
            Setting::where('name','autotrader_ftp_host')->update(['value' => $request->get('autotrader_ftp_host')]);
            Setting::where('name','autotrader_ftp_port')->update(['value' => $request->get('autotrader_ftp_port')]);
            Setting::where('name','autotrader_ftp_username')->update(['value' => $request->get('autotrader_ftp_username')]);
            Setting::where('name','autotrader_ftp_password')->update(['value' => $request->get('autotrader_ftp_password')]);
            Setting::where('name','enable_import_scheduler')->update(['value' => $request->get('enable_import_scheduler')]);
            Setting::where('name','import_scheduler')->update(['value' => json_encode($request->get('import_scheduler'))]);
            Setting::where('name','error_mail_to')->update(['value' => $request->get('error_mail_to')]);

            Cache::forget('settings');
            Cache::forget('translation');

            return redirect()->back()->withInput()->with('status', 'Settings updated!');
        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
