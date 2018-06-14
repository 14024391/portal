<?php

namespace App\Http\Controllers\Admin;

use App\Models\CabType;
use App\Models\Colour;
use App\Models\EmissionClass;
use App\Models\FuelType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

class ImportFromCSVController extends Controller
{

    public function index(){
        return view('admin.csv.index');
    }

    public function processCSV(){
        try{
            $this->truncateTables();
            $this->deletePhotos();

            if(Storage::exists("vehicle_csv/AMCommercials.xlsx")){
                $inputFileType = 'Xlsx';
                $inputFileName = storage_path("app/vehicle_csv/AMCommercials.xlsx");
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                $total = count($rows) - 1;
                $count = 0;
                $emissions = ['Euro1' =>  1,'Euro2' =>  2,'Euro3' =>  3,'Euro4' =>  4,'Euro5' =>  5,'Euro6' =>  6];
                $powerUnit = ['BrakeHorsePower' => 'bhp','HorsePower' => 'hp', 'Killowatt' => 'kw'];
                $colours = Colour::pluck("id","colour");
                foreach ($rows as $index => $row){
                    if($index > 0){

                        $visibility = $row[0] == 'Not Advertised' ? 0 : 1;
                        $type = 'Truck';
                        $typeId = 1;
                        switch ($row[1]){
                            case 'T':
                                $type = 'Truck';
                                $typeId = '1';
                                break;
                            case 'P':
                                $type = 'Plant';
                                $typeId = '2';
                                break;
                            case 'F':
                                $type = 'Farm';
                                $typeId = '3';
                                break;
                            case 'C':
                                $type = 'Car';
                                $typeId = '4';
                                break;
                            case 'V':
                                $type = 'Van';
                                $typeId = '5';
                                break;
                        }

                        $categoryId = $row[2] ? $this->getCategoryId($typeId,$row[2]) : null;
                        $created_at = Carbon::createFromFormat("j-M-y",$row[3])->toDateTimeString();

                        $subCategoryId = $categoryId && $row[9] != null  ? $this->getSubCategoryId($categoryId,$row[9]) : null;
                        $makeId = $row[14] ? $this->getMakeId($typeId,$row[14]) : null;
                        $modelId = $row[17] ? $this->getModelId($makeId,$row[17]) : null;
                        $bodyType = $row[20] ? $this->getBodyType($typeId,$row[20]) : null;
                        $cabType = $row[24] ? $this->getCabType($row[24]) : null;

                        $vehicle = new Vehicle();
                        $vehicle->type_id = $typeId;
                        $vehicle->category = $categoryId;
                        $vehicle->subCategory = $subCategoryId;
                        $vehicle->make = $makeId;
                        $vehicle->model = $modelId;
                        $vehicle->derivative = $row[11];
                        $vehicle->soldStatus = $row[6] == 'InStock' ? 'In Stock' : 'Sold';
                        $vehicle->autoTraderWebYes = $visibility;
                        $vehicle->myWebYes = $visibility;
                        $vehicle->featured = 0;
                        $vehicle->latest = 0;
                        $vehicle->created_at = $created_at;
                        $vehicle->updated_at = $created_at;
                        $vehicle->save();

                        $vehicleId = $vehicle->id;

                        $vehicleDescription = new VehicleDescription();
                        $vehicleDescription->vehicle_id = $vehicleId;
                        $vehicleDescription->description =  $row[4];
                        $vehicleDescription->referenceNumber =  $row[5];
                        $vehicleDescription->save();

                        $vehicleDetails = new VehicleDetail();
                        $vehicleDetails->vehicle_id = $vehicleId;
                        $vehicleDetails->axleDrive = $row[23];
                        $vehicleDetails->bodyType = $bodyType;
                        $vehicleDetails->cabType = $cabType;
                        $vehicleDetails->isDriverLeftHandSide = 0;
                        $vehicleDetails->manufacturerYear = $row[34];
                        $vehicleDetails->mileage = $row[16];
                        $vehicleDetails->mileageUnit = $row[16] ? $row[15] == 'Kilometre' ? 'KM': 'Miles' : null;
                        $vehicleDetails->hoursUsed = null;
                        $vehicleDetails->save();


                        $vehicleEnginInfo = new VehicleEngineInfo();
                        $vehicleEnginInfo->vehicle_id = $vehicleId;
                        $vehicleEnginInfo->capacity = $row[13];
                        $vehicleEnginInfo->capacityUnit = $row[13] && $row[12] == 'CubicCentimetreCC'? 'cc' : null;
                        $vehicleEnginInfo->emissions = $row[38] && $emissions[$row[38]] ? $emissions[$row[38]] : null;
                        $vehicleEnginInfo->fuelType = $row[21] ? $this->getFuelType($typeId,$row[21]) : null;
                        $vehicleEnginInfo->power = $row[31];
                        $vehicleEnginInfo->powerUnit = $row[31] && $row[30] ? $powerUnit[$row[30]] : null;
                        $vehicleEnginInfo->transmission = $row[22];
                        $vehicleEnginInfo->save();

                        $vehicleCondition = new VehicleCondition();
                        $vehicleCondition->vehicle_id = $vehicleId;
                        $vehicleCondition->bodyCondition = $row[25];
                        $vehicleCondition->interiorCondition = $row[26];
                        $vehicleCondition->tyreCondition = $row[29];
                        $vehicleCondition->overallCondition = $row[28];
                        $vehicleCondition->save();

                        $motdate = $row[41] ? \DateTime::createFromFormat('Yd', $row[41]) : null;
                        $vehicleHistory = new VehicleHistory();
                        $vehicleHistory->vehicle_id = $vehicleId;
                        $vehicleHistory->hoursUsed = null;
                        $vehicleHistory->lastInspectionDate = '';
                        $vehicleHistory->motDate = $motdate ? $motdate->format("M-Y"): null;
                        $vehicleHistory->previousOwner = $row[35];
                        $vehicleHistory->serviceHistory = $row[39];
                        $vehicleHistory->save();

                        $vprice = new VehiclePriceDetail();
                        $vprice->vehicle_id = $vehicleId;
                        $vprice->saleprice = $row[7];
                        $vprice->priceCurrency = 'GBP';
                        $vprice->poa = $row[8] == 'Plus VAT' ? 1 : 0;
                        $vprice->vat = '';
                        $vprice->soldStatus = $row[6] == 'InStock' ? 'In Stock' : 'Sold';
                        $vprice->save();

                        $registration = new VehicleRegistration();
                        $registration->vehicle_id = $vehicleId;
                        $registration->vehicleNew = 0;
                        $registration->registrationMonth = '';
                        $registration->registrationPlate = '';
                        $registration->registrationYear = $row[19];
                        $registration->serialNumber = $row[18];
                        $registration->save();

                        $vSpecifications = new VehicleSpecification();
                        $vSpecifications->vehicle_id = $vehicleId;
                        $vSpecifications->colour = $row[10] ? $colours[$row[10]] : null;
                        $vSpecifications->numberOfSeats = $row[36];
                        $vSpecifications->trailerAxis = $row[37];
                        $vSpecifications->weightUnit = $row[33] && $row[32] == 'Kilogram' ? 'Kg' : null;
                        $vSpecifications->grossWeight = $row[33];
                        $vSpecifications->save();

                        $vehicleFeature = new VehicleFeature();
                        $vehicleFeature->vehicle_id = $vehicle->id;
                        $vehicleFeature->features = json_encode([]);
                        $vehicleFeature->save();

                        $c = 0;
                        $images = $row[42] ? explode(",",$row[42]) : null;
                        if($images){
                            foreach ($images as $image){
                                $vehicleImage = new VehicleImage();
                                $image = trim($image);
                                $contents = fopen($image,'r');
                                $filename = uniqid().'.jpeg';
                                Storage::disk('photos')->put($filename, $contents);
                                $vehicleImage->vehicle_id = $vehicleId;
                                $vehicleImage->image = 'photos/'.$filename;
                                $vehicleImage->default = $c == 0 ? 1 : 0;
                                $vehicleImage->position = $c++;
                                $vehicleImage->save();
                            }
                        }
                        $count++;
                    }

                }
                return redirect()->back()->with("success","Imported $count records out of $total records");
            }
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

    function getCabType($cabType){
        CabType::firstOrCreate(['cab_type' => $cabType]);
        return $cabType;
    }

    function getFuelType($typeId, $fueltype){
        $fuelType = FuelType::firstOrCreate(['type_id' => $typeId,'fuel_type' => $fueltype]);
        return $fuelType->id;
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
