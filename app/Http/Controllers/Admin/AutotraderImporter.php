<?php

namespace App\Http\Controllers\Admin;

use App\Mail\ImportMail;
use App\Models\AutotraderImport;
use App\Models\BodyType;
use App\Models\Colour;
use App\Models\FuelType;
use App\Models\Setting;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AutotraderImporter extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function index(){
        $this->authorize('create', AutotraderImport::class);
        $imports = AutotraderImport::orderBy('created_at','desc')->limit(10)->get();
        return view('admin.autotrader.index',['imports' => $imports]);
    }

    /**
     * Create Autotrader Import File
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request){
        $this->authorize('create', AutotraderImport::class);
        $request->validate([
            'stockStatus' => 'required',
            'advertStatus' => 'required'
        ]);
        try{
            $settings = Setting::pluck('value','name');
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columnsStr = "feedID,channel,vehicleID,isUsed,regFull,serialNumber,make,model,derivative,yearOfManufacture,regYear,regYearCode,category,subCategory,
            bodyType,cabType,doors,seats,operatingType,wheelbaseType,wheelbaseValue,wheelbaseunit,axleConfig,enginePowerValue,enginePowerUnit,emissionClass,engineCapacityValue,engineCapacityUnit,fuelType,capacityWeightValue,capacityWeightUnit,gvwValue,gvwUnit,driverLeftOrRight,transmissionType,transmissionDriveType,colour,mileageValue,mileageUnit,taxYearMonth,motYearMonth,lastInspectionDate,serviceHistory,conditionOverall,conditionBody,conditionInterior,conditionMechanical,conditionTyresOverall,owners,price,priceLease,priceCurrency,priceSuffix,pricePoaFlag,descriptionText,advertDestination,pictureRefs";

            $vehicles = Vehicle::leftJoin('categories', 'vehicles.category', 'categories.id')
                ->leftJoin('sub_categories', 'vehicles.subcategory', 'sub_categories.id')
                ->leftJoin('makes', 'vehicles.make', 'makes.id')
                ->leftJoin('models', 'vehicles.model', 'models.id')
                ->whereIn('soldStatus',$request->stockStatus)
                ->whereIn('autoTraderWebYes',$request->advertStatus)
                ->select('vehicles.*','categories.category','sub_categories.sub_category', 'makes.make','models.model')->get();

            $rows = [];
            $fuelTypes = FuelType::pluck('fuel_type','id');
            $colours = Colour::pluck('colour','id');
            foreach ($vehicles as $vehicle){
                $row = [];
                $details = $vehicle->details;
                $engineInfo = $vehicle->engineInfo;
                $condition = $vehicle->condition;
                $specs = $vehicle->specifications;
                $price = $vehicle->price;


                $row['feedID'] = $settings['autotrader_client_id'];
                $row['channel'] = $vehicle->type->type;
                $row['vehicleID'] = $vehicle->id;
                $row['isUsed'] = $vehicle->registration->vehicleNew == 0 ? 'Y' : 'N';
                $row['regFull'] = $vehicle->registration->serialNumber;
                $row['serialNumber'] = $vehicle->registration->serialNumber;
                $row['make'] = $vehicle->make;
                $row['model'] = $vehicle->model;
                $row['derivative'] = $vehicle->derivative;
                $row['yearOfManufacture'] = intval($details->manufacturerYear) == 0 ? "" : intval($details->manufacturerYear);
                $row['regYear'] = intval($vehicle->registration->registrationYear) == 0 ? "" : intval($vehicle->registration->registrationYear);
                $row['regYearCode'] = $vehicle->registration->registrationPlate;
                $row['category'] = $vehicle->category;
                $row['subCategory'] = $vehicle->sub_category;
                $row['bodyType'] = $details->bodyType;
                $row['cabType'] = $details->cabType;
                $row['doors'] = $specs->numberOfDoors ? intval($specs->numberOfDoors) : null;
                $row['seats'] = $specs->numberOfSeats;
                $row['operatingType'] = $specs->operatingType;
                $row['wheelbaseType'] = $specs->wheelbaseType;
                $row['wheelbaseValue'] = $specs->wheelbase ? floatval($specs->wheelbase) : null;
                $row['wheelbaseunit'] = $specs->wheelbaseUnit;
                $row['axleConfig'] = $details->axleDrive;
                $row['trailerAxles'] = $specs->trailerAxis ? intval($specs->trailerAxis) : null;
                $row['enginePowerValue'] = $engineInfo->power ? intval($engineInfo->power) : null;
                $row['enginePowerUnit'] = strtoupper($engineInfo->powerUnit);
                $row['emissionClass'] = $engineInfo->emissions ? 'Euro'.$engineInfo->emissions : '';
                $row['engineCapacityValue'] = intval($engineInfo->capacity);
                $row['engineCapacityUnit'] = strtoupper($engineInfo->capacityUnit);
                $row['fuelType'] = $engineInfo->fuelType ? $fuelTypes[$engineInfo->fuelType] : '';
                $row['capacityWeightValue'] = $engineInfo->capacity;
                $row['capacityWeightUnit'] = $engineInfo->capacityUnit == 'Kg' ? 'Kg' : 'Ton';
                $row['gvwValue'] = $specs->grossWeight;
                $row['gvwUnit'] = strtoupper($specs->weightUnit);
                $row['gtwValue'] = $specs->trailerWeight;
                $row['gtwUnit'] = strtoupper($specs->trailerWeightUnit);
                $row['driverLeftOrRight'] = $details->isDriverLeftHandSide == 1 ? 'left' : 'right';
                $row['transmissionType'] = $engineInfo->transmission;
                $row['transmissionDriveType'] = '';
                $row['colour'] = $specs->colour ? $colours[$specs->colour] : '';
                $row['hours'] = $details->hoursUsed ? intval($details->hoursUsed) : null;
                $row['mileageValue'] = $details->mileage;
                $row['mileageUnit'] = $details->mileageUnit;
                $row['taxYearMonth'] = '';
                $row['motYearMonth'] = $vehicle->history->motDate ? date('Ym',strtotime($vehicle->history->motDate)) : '';
                $row['lastInspectionDate'] = $vehicle->history->lastInspectionDate ? date('d/m/Y',strtotime($vehicle->history->lastInspectionDate)) : '';
                $row['serviceHistory'] = $vehicle->history->serviceHistory;
                $row['conditionOverall'] = intval($condition->overallCondition);
                $row['conditionBody'] = intval($condition->bodyCondition);
                $row['conditionInterior'] = intval($condition->interiorCondition);
                $row['conditionMechanical'] = 0;
                $row['conditionTyresOverall'] = intval($condition->tyreCondition);
                $row['owners'] = $vehicle->history->previousOwner ? intval($vehicle->history->previousOwner) : null;
                $row['price'] = intval($price->saleprice);
                $row['priceLease'] = $price->leasePrice ? intval($price->leasePrice) : null;
                $row['priceCurrency'] = 'GBP';
                $row['priceSuffix'] = '';
                $row['pricePoaFlag'] = $price->poa == 1 ? 'Y' : null;
                $row['capacityLiftingValue'] = floatval($specs->liftingCapacity) ? floatval($specs->liftingCapacity) : null;
                $row['capacityLiftingUnit'] = $specs->liftingCapacityUnit;
                $row['maxheightValue'] = floatval($specs->maxheightValue) ? floatval($specs->maxheightValue) : null;
                $row['maxheightUnit'] = $specs->maximumHeightUnit;
                $row['maxReachValue'] = floatval($specs->maximumReach) ? floatval($specs->maximumReach) : null;
                $row['maxReachUnit'] = $specs->maximumReachUnit;
                $row['maxSpeedValue'] = intval($specs->speed) ? intval($specs->speed) : null;
                $row['maxSpeedUnit'] = $specs->speedUnit;
                $row['operatingWidthValue'] = floatval($specs->operatingWidth) ? floatval($specs->operatingWidth) : null;
                $row['operatingWidthUnit'] = $specs->operatingWidthUnit;
                $row['externalheightValue'] = floatval($specs->externalHeight) ? floatval($specs->externalHeight) : null;
                $row['externalheightUnit'] = $specs->externalUnit;
                $row['externallengthValue'] = floatval($specs->externalLength) ? floatval($specs->externalLength) : null;
                $row['externallengthUnit'] = $specs->externalUnit;
                $row['externalwidthValue'] = floatval($specs->externalWidth) ? floatval($specs->externalWidth) : null;
                $row['externalwidthUnit'] = $specs->externalUnit;
                $row['descriptionText'] = $vehicle->description->description;
                $advertDestination = "";
                if ($vehicle->autoTraderWebYes == 1 && $vehicle->myWebYes == 1) $advertDestination = 'A';
                elseif ($vehicle->autoTraderWebYes == 1 && $vehicle->myWebYes == 0) $advertDestination = 'T';
                elseif ($vehicle->autoTraderWebYes == 0 && $vehicle->myWebYes == 1) $advertDestination = 'W';
                $row['advertDestination'] = $advertDestination;
                $images = $vehicle->images()->orderBy('default','desc')->pluck('image')->toArray();
                if(count($images) > 0){
                    $imageArr = [];
                    foreach ($images as $image){
                        array_push($imageArr,'http://portal.amcommercials.co.uk/storage/'.$image);
                    }
                    $images = implode(",",$imageArr);
                }else{
                    $images = "";
                }
                $row['pictureRefs'] = $images;

                array_push($rows,$row);
            }
            array_unshift($rows,array_keys($rows[0]));
            $fileTime = date('dmY_Hi');
            $fileName = $settings['autotrader_supplier_name']."-".$fileTime."-stock.csv";
            $fp = fopen(storage_path('app/exports/').$fileName, 'w');

            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);

            $importer = new AutotraderImport();
            $importer->file_name = $fileName;
            $records =  $vehicles->count();
            $importer->records = $records;
            $importer->processed_at = null;
            $importer->save();

            return redirect()->back()->with('status','Successfully Created the Import File');
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Ran by Scheduler Job
     * Create Autotrader Import File
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createByScheduler(){
        try{
            $settings = Setting::pluck('value','name');
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columnsStr = "feedID,channel,vehicleID,isUsed,regFull,serialNumber,make,model,derivative,yearOfManufacture,regYear,regYearCode,category,subCategory,
            bodyType,cabType,doors,seats,operatingType,wheelbaseType,wheelbaseValue,wheelbaseunit,axleConfig,enginePowerValue,enginePowerUnit,emissionClass,engineCapacityValue,engineCapacityUnit,fuelType,capacityWeightValue,capacityWeightUnit,gvwValue,gvwUnit,driverLeftOrRight,transmissionType,transmissionDriveType,colour,mileageValue,mileageUnit,taxYearMonth,motYearMonth,lastInspectionDate,serviceHistory,conditionOverall,conditionBody,conditionInterior,conditionMechanical,conditionTyresOverall,owners,price,priceLease,priceCurrency,priceSuffix,pricePoaFlag,descriptionText,advertDestination,pictureRefs";

            $vehicles = Vehicle::leftJoin('categories', 'vehicles.category', 'categories.id')
                ->leftJoin('sub_categories', 'vehicles.subcategory', 'sub_categories.id')
                ->leftJoin('makes', 'vehicles.make', 'makes.id')
                ->leftJoin('models', 'vehicles.model', 'models.id')
                ->whereIn('soldStatus',['In Stock'])
                ->whereIn('autoTraderWebYes',[1])
                ->select('vehicles.*','categories.category','sub_categories.sub_category', 'makes.make','models.model')
                ->get();

            $rows = [];
            $fuelTypes = FuelType::pluck('fuel_type','id');
            $colours = Colour::pluck('colour','id');
            foreach ($vehicles as $vehicle){
                $row = [];
                $details = $vehicle->details;
                $engineInfo = $vehicle->engineInfo;
                $condition = $vehicle->condition;
                $specs = $vehicle->specifications;
                $price = $vehicle->price;


                $row['feedID'] = $settings['autotrader_client_id'];
                $row['channel'] = $vehicle->type->type;
                $row['vehicleID'] = $vehicle->id;
                $row['isUsed'] = $vehicle->registration->vehicleNew == 0 ? 'Y' : 'N';
                $row['regFull'] = $vehicle->registration->serialNumber;
                $row['serialNumber'] = $vehicle->registration->serialNumber;
                $row['make'] = $vehicle->make;
                $row['model'] = $vehicle->model;
                $row['derivative'] = $vehicle->derivative;
                $row['yearOfManufacture'] = intval($details->manufacturerYear) == 0 ? "" : intval($details->manufacturerYear);
                $row['regYear'] = intval($vehicle->registration->registrationYear) == 0 ? "" : intval($vehicle->registration->registrationYear);
                $row['regYearCode'] = $vehicle->registration->registrationPlate;
                $row['category'] = $vehicle->category;
                $row['subCategory'] = $vehicle->sub_category;
                $row['bodyType'] = $details->bodyType;
                $row['cabType'] = $details->cabType;
                $row['doors'] = $specs->numberOfDoors ? intval($specs->numberOfDoors) : null;
                $row['seats'] = $specs->numberOfSeats;
                $row['operatingType'] = $specs->operatingType;
                $row['wheelbaseType'] = $specs->wheelbaseType;
                $row['wheelbaseValue'] = $specs->wheelbase ? floatval($specs->wheelbase) : null;
                $row['wheelbaseunit'] = $specs->wheelbaseUnit;
                $row['axleConfig'] = $details->axleDrive;
                $row['trailerAxles'] = $specs->trailerAxis ? intval($specs->trailerAxis) : null;
                $row['enginePowerValue'] = $engineInfo->power ? intval($engineInfo->power) : null;
                $row['enginePowerUnit'] = strtoupper($engineInfo->powerUnit);
                $row['emissionClass'] = $engineInfo->emissions ? 'Euro'.$engineInfo->emissions : '';
                $row['engineCapacityValue'] = intval($engineInfo->capacity);
                $row['engineCapacityUnit'] = strtoupper($engineInfo->capacityUnit);
                $row['fuelType'] = $engineInfo->fuelType ? $fuelTypes[$engineInfo->fuelType] : '';
                $row['capacityWeightValue'] = $engineInfo->capacity;
                $row['capacityWeightUnit'] = $engineInfo->capacityUnit == 'Kg' ? 'Kg' : 'Ton';
                $row['gvwValue'] = $specs->grossWeight;
                $row['gvwUnit'] = strtoupper($specs->weightUnit);
                $row['gtwValue'] = $specs->trailerWeight;
                $row['gtwUnit'] = strtoupper($specs->trailerWeightUnit);
                $row['driverLeftOrRight'] = $details->isDriverLeftHandSide == 1 ? 'left' : 'right';
                $row['transmissionType'] = $engineInfo->transmission;
                $row['transmissionDriveType'] = '';
                $row['colour'] = $specs->colour ? $colours[$specs->colour] : '';
                $row['hours'] = $details->hoursUsed ? intval($details->hoursUsed) : null;
                $row['mileageValue'] = $details->mileage;
                $row['mileageUnit'] = $details->mileageUnit;
                $row['taxYearMonth'] = '';
                $row['motYearMonth'] = $vehicle->history->motDate ? date('Ym',strtotime($vehicle->history->motDate)) : '';
                $row['lastInspectionDate'] = $vehicle->history->lastInspectionDate ? date('d/m/Y',strtotime($vehicle->history->lastInspectionDate)) : '';
                $row['serviceHistory'] = $vehicle->history->serviceHistory;
                $row['conditionOverall'] = intval($condition->overallCondition);
                $row['conditionBody'] = intval($condition->bodyCondition);
                $row['conditionInterior'] = intval($condition->interiorCondition);
                $row['conditionMechanical'] = 0;
                $row['conditionTyresOverall'] = intval($condition->tyreCondition);
                $row['owners'] = $vehicle->history->previousOwner ? intval($vehicle->history->previousOwner) : null;
                $row['price'] = intval($price->saleprice);
                $row['priceLease'] = $price->leasePrice ? intval($price->leasePrice) : null;
                $row['priceCurrency'] = 'GBP';
                $row['priceSuffix'] = '';
                $row['pricePoaFlag'] = $price->poa == 1 ? 'Y' : null;
                $row['capacityLiftingValue'] = floatval($specs->liftingCapacity) ? floatval($specs->liftingCapacity) : null;
                $row['capacityLiftingUnit'] = $specs->liftingCapacityUnit;
                $row['maxheightValue'] = floatval($specs->maxheightValue) ? floatval($specs->maxheightValue) : null;
                $row['maxheightUnit'] = $specs->maximumHeightUnit;
                $row['maxReachValue'] = floatval($specs->maximumReach) ? floatval($specs->maximumReach) : null;
                $row['maxReachUnit'] = $specs->maximumReachUnit;
                $row['maxSpeedValue'] = intval($specs->speed) ? intval($specs->speed) : null;
                $row['maxSpeedUnit'] = $specs->speedUnit;
                $row['operatingWidthValue'] = floatval($specs->operatingWidth) ? floatval($specs->operatingWidth) : null;
                $row['operatingWidthUnit'] = $specs->operatingWidthUnit;
                $row['externalheightValue'] = floatval($specs->externalHeight) ? floatval($specs->externalHeight) : null;
                $row['externalheightUnit'] = $specs->externalUnit;
                $row['externallengthValue'] = floatval($specs->externalLength) ? floatval($specs->externalLength) : null;
                $row['externallengthUnit'] = $specs->externalUnit;
                $row['externalwidthValue'] = floatval($specs->externalWidth) ? floatval($specs->externalWidth) : null;
                $row['externalwidthUnit'] = $specs->externalUnit;
                $row['descriptionText'] = $vehicle->description->description;
                $advertDestination = "";
                if ($vehicle->autoTraderWebYes == 1 && $vehicle->myWebYes == 1) $advertDestination = 'A';
                elseif ($vehicle->autoTraderWebYes == 1 && $vehicle->myWebYes == 0) $advertDestination = 'T';
                elseif ($vehicle->autoTraderWebYes == 0 && $vehicle->myWebYes == 1) $advertDestination = 'W';
                $row['advertDestination'] = $advertDestination;
                $images = $vehicle->images()->orderBy('default','desc')->pluck('image')->toArray();
                if(count($images) > 0){
                    $imageArr = [];
                    foreach ($images as $image){
                        array_push($imageArr,'http://portal.amcommercials.co.uk/storage/'.$image);
                    }
                    $images = implode(",",$imageArr);
                }else{
                    $images = "";
                }
                $row['pictureRefs'] = $images;

                array_push($rows,$row);
            }
            array_unshift($rows,array_keys($rows[0]));
            $fileTime = date('dmY_Hi');
            $fileName = $settings['autotrader_supplier_name']."-".$fileTime."-stock.csv";
            $fp = fopen(storage_path('app/exports/').$fileName, 'w');

            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);

            $importer = new AutotraderImport();
            $importer->file_name = $fileName;
            $records =  $vehicles->count();
            $importer->records = $records;
            $importer->processed_at = null;
            $importer->save();

            return ['error' => false, 'errorMessage' => '','importerId' => $importer->id];
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return ['error' => true, 'errorMessage' => $e->getMessage()];
        }
    }

    /*
     * Download File
     * */

    public function download($id){
        try{

            $importer = AutotraderImport::find($id);
            return Storage::disk('exports')->download($importer->file_name);
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /*
     * Delete the File and records from database
     */

    public function delete($id){
        try{
            $importer = AutotraderImport::find($id);
            Storage::disk('exports')->delete($importer->file_name);
            AutotraderImport::destroy($id);
            return redirect()->back()->with('status', 'Successfully Deleted the File');
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process the Export File
     * */

    public function process($id){
        try{
            $settings = Setting::pluck('value','name');
            $importer = AutotraderImport::find($id);

            $ftp_server = $settings['autotrader_ftp_host'];
            $ftp_username = $settings['autotrader_ftp_username'];
            $ftp_userpass = $settings['autotrader_ftp_password'];

            $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
            ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
            ftp_pasv($ftp_conn, true) or die("Cannot switch to passive mode");
            $file = storage_path("app/exports/".$importer->file_name);
            $isUpload = false;
            if (ftp_put($ftp_conn, $importer->file_name, $file, FTP_ASCII))
            {
                $isUpload = true;
                $importer->processed_at = Carbon::now();
                $importer->save();
            }
            // close connection
            ftp_close($ftp_conn);
            if(!$isUpload){
                return redirect()->back()->with('status', "Error Connecting to FTP Server. Please contact Support Team");
            }
            return redirect()->back()->with('status', 'Successfully Uploaded the File to AutoTrader Portal');
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Process the Export File
     * */

    public function processByScheduler($id){
        $settings = Setting::pluck('value','name');
        try{
            $importer = AutotraderImport::find($id);

            $ftp_server = $settings['autotrader_ftp_host'];
            $ftp_username = $settings['autotrader_ftp_username'];
            $ftp_userpass = $settings['autotrader_ftp_password'];

            $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
            ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
            ftp_pasv($ftp_conn, true) or die("Cannot switch to passive mode");
            $file = storage_path("app/exports/".$importer->file_name);
            $isUpload = false;
            if (ftp_put($ftp_conn, $importer->file_name, $file, FTP_ASCII))
            {
                $isUpload = true;
                $importer->processed_at = Carbon::now();
                $importer->save();
            }
            // close connection
            ftp_close($ftp_conn);
            if(!$isUpload){
                Log::error('Error Connecting to FTP Server. Please contact Support Team');
            }
            Log::info('Successfully Uploaded the File to AutoTrader Portal');
        }catch (\Exception $e){
            Log::error($e->getTraceAsString());
            Mail::to($settings['error_mail_to'])->send(new ImportMail($e->getMessage()));
        }
    }
}
