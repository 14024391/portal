<?php
/*
 * Get Data from AutoExporter
 *
 * */
namespace App\Http\Controllers\Admin;

use App\Models\BodyType;
use App\Models\Category;
use App\Models\Make;
use App\Models\Setting;
use App\Models\SubCategory;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\VehicleCondition;
use App\Models\VehicleDescription;
use App\Models\VehicleDetail;
use App\Models\VehicleEngineInfo;
use App\Models\VehicleFeature;
use App\Models\VehicleHistory;
use App\Models\VehicleImage;
use App\Models\VehicleModel;
use App\Models\VehiclePriceDetail;
use App\Models\VehicleRegistration;
use App\Models\VehicleSpecification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AutotraderExporter extends Controller
{

    private $apiKey = "";
    private $customerId = "";

    public function __construct(){

        $settings = Setting::all();
        $this->apiKey = $settings->where('name','autotrader_importer_api_key')->pluck('value')[0];
        $this->customerId = $settings->where('name','autotrader_importer_customer_id')->pluck('value')[0];
    }

    public function index(){
        return view('admin.autotrader.exporter');

    }
    public function process(){
        try{
            $curl = curl_init();
            $apiKey = $this->apiKey;
            $customerId = $this->customerId;
            //$url = "http://ct-exports.autotrader.co.uk/api/get-stock/?api_key=%s&customer_id=%s";
            $url = "";
            $url = sprintf($url,  $apiKey, $customerId);
            //$url = "http://localhost:3000/ct-exports.autotrader.co.uk.xml";

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $data = curl_exec($curl);
            curl_close($curl);

            $xmlData = simplexml_load_string($data,'SimpleXMLElement');
            //$jsonData = json_decode(json_encode($xmlData));

            $this->truncateTables();
            $this->deletePhotos();
            $totalRecords = count($xmlData->listing);
            $recordCount = 0;
            foreach($xmlData->listing as $item)
            {
                $type = $this->getType($item->channel);
                if ($type){
                    $typeId = $type->id;
                    $categoryId = $item->category ? $this->getCategoryId($typeId,$item->category) : null;
                    $subCategoryId = $categoryId && isset($item->subCategory)  ? $this->getSubCategoryId($categoryId,$item->subCategory) : null;
                    $makeId = isset($item->properties->make) ? $this->getMakeId($typeId,$item->properties->make) : null;
                    $modelId = isset($item->properties->model) ? $this->getModelId($makeId,$item->properties->model) : null;
                    $bodyType = isset($item->properties->bodyType) ? $this->getBodyType($typeId,$item->properties->bodyType) : null;

                    $vehicle = new Vehicle();
                    $vehicle->type_id = $typeId;
                    $vehicle->category = $categoryId;
                    $vehicle->subCategory = $subCategoryId;
                    $vehicle->make = $makeId;
                    $vehicle->model = $modelId;
                    $vehicle->derivative = "";
                    $vehicle->soldStatus = 'In Stock';
                    $vehicle->autoTraderWebYes = 1;
                    $vehicle->myWebYes = 1;
                    $vehicle->featured = 0;
                    $vehicle->latest = 0;
                    $vehicle->save();

                    $vehicleId = $vehicle->id;

                    $vehicleCondition = new VehicleCondition();
                    $vehicleCondition->vehicle_id = $vehicleId;
                    $vehicleCondition->bodyCondition = '';
                    $vehicleCondition->interiorCondition = '';
                    $vehicleCondition->overallCondition = '';
                    $vehicleCondition->tyreCondition = '';
                    $vehicleCondition->save();

                    $vehicleDescription = new VehicleDescription();
                    $vehicleDescription->vehicle_id = $vehicleId;
                    $vehicleDescription->description =  isset($item->description) ? $item->description : null;
                    $vehicleDescription->referenceNumber =  '';
                    $vehicleDescription->save();

                    $vehicleDetails = new VehicleDetail();
                    $vehicleDetails->vehicle_id = $vehicleId;
                    $vehicleDetails->axleDrive = isset($item->properties->axleConfig) ? $item->properties->axleConfig : null;
                    $vehicleDetails->bodyType = $bodyType;
                    $vehicleDetails->cabType = '';
                    $vehicleDetails->isDriverLeftHandSide = null;
                    $vehicleDetails->manufacturerYear = isset($item->properties->manufactureYear) ? $item->properties->manufactureYear : null;
                    $vehicleDetails->mileage = isset($item->properties->mileage) ? $item->properties->mileage : null;
                    $vehicleDetails->mileageUnit = isset($item->properties->mileage) ? $item->properties->mileage['unit']: '';
                    $vehicleDetails->hoursUsed = isset($item->properties->hoursUsed) ? $item->properties->hoursUsed : null;
                    $vehicleDetails->save();

                    $vehicleEnginInfo = new VehicleEngineInfo();
                    $vehicleEnginInfo->vehicle_id = $vehicleId;
                    $vehicleEnginInfo->capacity = null;
                    $vehicleEnginInfo->capacityUnit = null;
                    $vehicleEnginInfo->emissions = null;
                    $vehicleEnginInfo->fuelType = null;
                    $vehicleEnginInfo->power = null;
                    $vehicleEnginInfo->powerUnit = null;
                    $vehicleEnginInfo->transmission = null;
                    $vehicleEnginInfo->save();

                    $vehicleFeature = new VehicleFeature();
                    $vehicleFeature->vehicle_id = $vehicle->id;
                    $vehicleFeature->features = json_encode([]);
                    $vehicleFeature->save();
                    $count = 0;

                    $vehicleHistory = new VehicleHistory();
                    $vehicleHistory->vehicle_id = $vehicleId;
                    $vehicleHistory->hoursUsed = isset($item->hoursUsed) ? $item->hoursUsed : null;
                    $vehicleHistory->lastInspectionDate = '';
                    $vehicleHistory->motDate = null;
                    $vehicleHistory->previousOwner = '';
                    $vehicleHistory->serviceHistory = '';
                    $vehicleHistory->save();



                    foreach ($item->images->image as $image){
                        $vehicleImage = new VehicleImage();
                        $contents = file_get_contents($image);
                        $filename = uniqid().'.jpeg';
                        Storage::disk('photos')->put($filename, $contents);
                        $vehicleImage->vehicle_id = $vehicleId;
                        $vehicleImage->image = 'photos/'.$filename;
                        $vehicleImage->default = $count == 0 ? 1 : 0;
                        $vehicleImage->position = $count++;
                        $vehicleImage->save();
                    }

                    $vprice = new VehiclePriceDetail();
                    $vprice->vehicle_id = $vehicleId;
                    $vprice->saleprice = isset($item->price) ? intval($item->price) : 0;
                    $vprice->priceCurrency = isset($item->price['unit']) ? $item->price['unit'] : 'GBP';
                    $vprice->poa = isset($item->poa) ? $item->poa : 0;
                    $vprice->vat = '';
                    $vprice->soldStatus = '';
                    $vprice->save();

                    $registration = new VehicleRegistration();
                    $registration->vehicle_id = $vehicleId;
                    $registration->vehicleNew = isset($item->newUsed) && $item->newUsed == 'new'? 1 : 0;
                    $registration->registrationMonth = '';
                    $registration->registrationPlate = '';
                    $registration->registrationYear = isset($item->properties->regYear) ? $item->properties->regYear : null;
                    $registration->serialNumber = '';
                    $registration->save();

                    $vSpecifications = new VehicleSpecification();
                    $vSpecifications->vehicle_id = $vehicleId;
                    $vSpecifications->save();
                    $recordCount++;
                }

            }

            return redirect()->back()->with('success',"Total Records : $totalRecords, Processed Records : $recordCount");
        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    function getType($type){
        return Type::whereRaw('lower(type) = ?',strtolower($type))->first();
    }

    function getCategoryId($typeId,$cat){
        $category =  Category::firstOrCreate(['type_id' => $typeId,'category' => $cat]);
        return $category->id;
    }

    function getSubCategoryId($categoryId, $subCat){
        $subCategory = SubCategory::firstOrCreate(['category_id' => $categoryId,'sub_category' => $subCat]);
        return $subCategory->id;
    }

    function getMakeId($typeId,$make){
        $make =  Make::firstOrCreate(['type_id' => $typeId,'make' => $make]);
        return $make->id;
    }

    function getModelId($makeId, $model){
        $vehicleModel = VehicleModel::firstOrCreate(['make_id' => $makeId,'model' => $model]);
        return $vehicleModel->id;
    }

    function getBodyType($typeId,$bodyType){
        $vBodyType = BodyType::firstOrCreate(['type_id' => $typeId,'body_type' => $bodyType]);
        return $vBodyType->body_type;
    }


    public function truncateTables(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('vehicle_specifications')->truncate();
        DB::table('vehicle_registrations')->truncate();
        DB::table('vehicle_price_details')->truncate();
        DB::table('vehicle_links')->truncate();
        DB::table('vehicle_images')->truncate();
        DB::table('vehicle_history')->truncate();
        DB::table('vehicle_features')->truncate();
        DB::table('vehicle_engine_info')->truncate();
        DB::table('vehicle_details')->truncate();
        DB::table('vehicle_descriptions')->truncate();
        DB::table('vehicle_condition')->truncate();
        DB::table('vehicles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function deletePhotos(){
        $files = Storage::disk('public')->allFiles('photos');
        foreach($files as $file){
            Storage::disk('public')->delete($file);
        }
    }
}
