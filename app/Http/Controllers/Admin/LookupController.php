<?php

namespace App\Http\Controllers\Admin;

use App\Models\BodyType;
use App\Models\CabType;
use App\Models\FuelType;
use App\Models\Make;
use App\Models\Setting;
use App\Models\Type;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LookupController extends Controller
{
    /*
     * DVLA Lookup
     **/
    private $ApiKey = '';
    private $limit = 50;
    private $usages = 0;
    private $type = null;
    private $type_id = null;

    public function __construct()
    {
        $settings = Setting::all();
        $this->ApiKey = $settings->where('name','dvla_api_key')->pluck('value')[0];
        $this->limit = $settings->where('name','dvla_daily_limit')->pluck('value')[0];
        $usages = $settings->where('name','dvla_daily_usages')->first();
        if($usages->updated_at->isToday()){
            $this->usages = $usages->value;
        }else{
            $this->usages = 0;
        }
        $usages->value = $this->usages + 1;
        $usages->save();
    }

    public function lookup(Request $request){
        if($this->usages > $this->limit){
            return response(['error' => true, 'data' => '','message' => "You have exceeded Daily DVLA lookup limit of ".$this->limit.". Please contact Administrator to increase daily limit."]);
        }
        $curl = curl_init();
        $ApiKey = $this->ApiKey;
        $this->type = $request->type;
        $url = "https://uk1.ukvehicledata.co.uk/api/datapackage/%s?v=2&api_nullitems=1&key_vrm=%s&auth_apikey=%s";
        $url = sprintf($url, "VehicleData", $request->vehicleVrm, $this->ApiKey);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = json_decode(curl_exec($curl));
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return response(['error' => true, 'data' => $error]);
        } else {
            switch ($response->Response->StatusCode){
                case 'KeyInvalid':
                    break;
                case 'SandboxLimitation':
                    break;
                case 'Success':
                    $this->updateVehicleData($response->Response->DataItems);
                    break;
            }
            return response(['error' => false, 'data' => $response]);
        }
    }

    public function updateVehicleData($data){
        $reg = $data->VehicleRegistration;
        $smmt = $data->SmmtDetails;
        $make = $this->updateMake($reg->Make);
        $this->updateModel($make, $reg->Model);
        if($smmt->FuelType){
            $this->updateFuelType($smmt->FuelType);
        }
        if($smmt->BodyStyle && $smmt->BodyStyle != 'NA' && $smmt->BodyStyle != ''){
            $this->updateBodyStyle($smmt->BodyStyle);
        }
        if($smmt->CabType && $smmt->CabType != 'NA' && $smmt->CabType != ''){
            $this->updateCabType($smmt->CabType);
        }
    }

    public function updateMake($make){
        return Make::firstOrCreate(['type_id' => $this->type['id'],'make' => ucfirst($make)]);
    }

    public function updateModel($make, $model){
        VehicleModel::firstOrCreate(['make_id' => $make->id,'model' => $model]);
    }

    public function updateFuelType($fuelType){
        FuelType::firstOrCreate(['type_id' => $this->type['id'],'fuel_type' => $fuelType]);
    }

    public function updateBodyStyle($bodyType){
        BodyType::firstOrCreate(['type_id' => $this->type['id'],'body_type' => $bodyType]);
    }

    public function updateCabType($cabType){
        CabType::firstOrCreate(['cab_type' => ucfirst(strtolower($cabType))]);
    }
}
