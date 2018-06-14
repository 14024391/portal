<?php

namespace App\Http\Controllers\Admin;

use App\Models\BodyType;
use App\Models\CabType;
use App\Models\Category;
use App\Models\Colour;
use App\Models\EmissionClass;
use App\Models\Feature;
use App\Models\FuelType;
use App\Models\Make;
use App\Models\RegistrationPlate;
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
use App\Models\VehicleLink;
use App\Models\VehicleModel;
use App\Models\VehiclePriceDetail;
use App\Models\VehicleRegistration;
use App\Models\VehicleSpecification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class VehicleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Vehicle::class);
        $vehicles = DB::table('vehicles')->leftJoin('types', 'vehicles.type_id', 'types.id')
            ->leftJoin('categories', 'vehicles.category', 'categories.id')
            ->leftJoin('vehicle_details', 'vehicles.id', 'vehicle_details.vehicle_id')
            ->leftJoin('vehicle_descriptions', 'vehicle_descriptions.vehicle_id', 'vehicles.id')
            ->leftJoin('vehicle_registrations', 'vehicles.id', 'vehicle_registrations.vehicle_id')
            ->leftJoin('vehicle_price_details', 'vehicles.id', 'vehicle_price_details.vehicle_id')
            ->leftJoin('makes', 'vehicles.make', 'makes.id')
            ->leftJoin('models', 'vehicles.model', 'models.id')
            ->whereNull('vehicles.deleted_at')
            ->select('vehicles.id', 'types.type', 'categories.category','vehicles.soldStatus',
                'vehicles.featured','vehicles.latest','vehicles.autoTraderWebYes','vehicles.myWebYes','vehicles.created_at'
                , 'vehicle_price_details.priceCurrency','vehicle_price_details.poa',
                'makes.make','models.model','vehicles.derivative',
                DB::raw("FORMAT(vehicle_price_details.saleprice,0) as saleprice"),
                DB::raw("IF(vehicle_registrations.registrationYear,vehicle_registrations.registrationYear,vehicle_details.manufacturerYear) as registrationYear"),
                DB::raw("IF(vehicle_registrations.serialNumber,vehicle_registrations.serialNumber,vehicle_descriptions.referenceNumber) as serialNumber"),
                DB::raw("DATE_FORMAT(vehicles.created_at,'%d-%m-%Y') as created_date"),
                DB::raw("DATE_FORMAT(vehicles.updated_at,'%d-%m-%Y %H:%i') as updated_at")
            )
            ->orderBy('created_at','desc')
            ->get();

        $vehicleImages = VehicleImage::where('default',1)->pluck('image','vehicle_id')->toArray();
        foreach ($vehicles as $vehicle){
            $vehicle->image = isset($vehicleImages[$vehicle->id])? '/storage/small/'.$vehicleImages[$vehicle->id] : '/images/no-vehicle-image.gif';
        }

        $stats = [];
        $stats['trucks'] = $vehicles->where('type', 'Truck')->count();
        $stats['plants'] = $vehicles->where('type', 'Plant')->count();
        $stats['farms'] = $vehicles->where('type', 'Farm')->count();
        $stats['cars'] = $vehicles->where('type', 'Car')->count();
        $stats['vans'] = $vehicles->where('type', 'Van')->count();
        return view('admin.vehicle.index', ['vehicles' => $vehicles, 'images' => $vehicleImages,'stats' => $stats]);
    }

    /**
     * Get Vehicle Data
     */

    public function getVehicleData($type)
    {
        $vehicleData = [
            'type' => $type,
            'categories' => Category::where('type_id', $type->id)->get(),
            'subcategories' => SubCategory::all(),
            'colours' => Colour::all(),
            'cabTypes' => CabType::all(),
            'bodyTypes' => BodyType::where('type_id', $type->id)->get(),
            'emissionClasses' => EmissionClass::all(),
            'fuelTypes' => FuelType::where('type_id', $type->id)->get(),
            'makes' => Make::where('type_id', $type->id)->get(),
            'models' => VehicleModel::all(),
            'registrationPlates' => RegistrationPlate::all(),
            'features' => Feature::where('type_id', $type->id)->get(),
        ];

        return $vehicleData;
    }

    /**
     * Get Vehicle Data
     */
    public function getUpdatedVehicleData($typeId){
        $type = Type::find($typeId);
        $vehicleData = $this->getVehicleData($type);
        return response()->json(['data' => $vehicleData]);
    }

    public function getVehicleJson($type, $v)
    {
        if ($v == null) {
            $data = [
                'action' => 'STORE',
                'details' => [
                    'vehicle_id' => null,
                    'updated_at' => null,
                    'type' => $type->type,
                    'type_id' => $type->id,
                    'category' => null,
                    'subcategory' => null,
                    'vehicleNew' => false,
                    'serialNumber' => null,
                    'make' => null,
                    'model' => null,
                    'derivative' => null,
                    'registrationYear' => null,
                    'registrationPlate' => null,
                    'mileage' => null,
                    'mileageUnit' => 'Miles',
                    'bodyType' => null,
                    'axleDrive' => null,
                    'cabType' => null,
                    'isLeftDriverPosition' => false,
                    'manufacturerYear' => null,
                    'salePrice' => null,
                    'salePriceCurrency' => 'GBP',
                    'vat' => true,
                    'poa' => false,
                    'monthlyLease' => null,
                    'monthlyLeaseCurrency' => 'GBP',
                ],
                'images' => [],
                'engineInfo' => [
                    'fuelType' => null,
                    'emissions' => '',
                    'power' => '',
                    'powerUnit' => 'hp',
                    'capacity' => '',
                    'capacityUnit' => 'cc',
                    'transmission' => ''
                ],
                'specifications' => [
                    'numberOfSeats' => '',
                    'colour' => '',
                    'maxPayload' => '',
                    'payloadUnit' => 'kg',
                    'grossWeight' => '',
                    'weightUnit' => 'kg',
                    'trailerWeight' => '',
                    'trailerWeightUnit' => 'kg',
                    'volume' => '',
                    'volumeUnit' => 'm3',
                    'internalHeight' => '',
                    'internalWidth' => '',
                    'internalLength' => '',
                    'internalUnit' => 'm',
                    'externalWidth' => '',
                    'externalHeight' => '',
                    'externalLength' => '',
                    'externalUnit' => 'm',
                    'liftingCapacity' => '',
                    'liftingCapacityUnit' => 'kg',
                    'maximumHeight' => '',
                    'maximumHeightUnit' => 'm',
                    'maximumReach' => '',
                    'maximumReachUnit' => 'm',
                    'operatingWidth' => '',
                    'operatingWidthUnit' => 'm',
                    'operatingType' => '',
                    'speedUnit' => 'mph',
                    'wheelbase' => '',
                    'wheelbaseUnit' => 'm',
                    'numberOfDoors' => null,
                    'speed' => null,
                    'trailerAxis'=> null,
                    'wheelbaseType' => null,

                ],
                'condition' => [
                    'bodyCondition' => '',
                    'interiorCondition' => '',
                    'overallCondition' => '',
                    'tyreCondition' => ''
                ],
                'history' => [
                    'previousOwner' => '',
                    'hoursUsed' => '',
                    'lastInspectionDate' => '',
                    'motDate' => '',
                    'previousOwners' => '',
                    'serviceHistory' => ''
                ],
                'features' => '',
                'videoLinks' => '',
                'description' => '',
                'referenceNumber' => '',
                'soldStatus' => 'In Stock',
                'autoTraderWebYes' => 0,
                'myWebYes' => 0,
                'featured' => 0,
                'latest' => 0,
            ];
            return $data;
        }
        $data = [
            'action' => 'UPDATE',
            'details' => [
                'vehicle_id' => $v->id,
                'updated_at' => $v->updated_at->diffForHumans(),
                'created_at' => $v->created_at->diffForHumans(),
                'type' => $v->type->type,
                'type_id' => $v->type->id,
                'category' => $v->category,
                'subcategory' => $v->subcategory,
                'vehicleNew' => $v->registration->vehicleNew == 1 ? true : false,
                'serialNumber' => $v->registration->serialNumber,
                'make' => $v->make,
                'model' => $v->model,
                'derivative' => $v->derivative,
                'registrationYear' => $v->registration->registrationYear,
                'registrationPlate' => $v->registration->registrationPlate,
                'mileage' => $v->details->mileage,
                'mileageUnit' => $v->details->mileageUnit ? $v->details->mileageUnit : 'Miles',
                'bodyType' => $v->details->bodyType,
                'axleDrive' => $v->details->axleDrive,
                'cabType' => $v->details->cabType,
                'isLeftDriverPosition' => $v->details->isLeftDriverPosition ? $v->details->isLeftDriverPosition : false,
                'manufacturerYear' => $v->details->manufacturerYear,
                'salePrice' => $v->price->saleprice,
                'salePriceCurrency' => $v->price->priceCurrency ? $v->price->priceCurrency : 'GBP',
                'vat' => $v->price->vat == 1 ? true : false,
                'poa' => $v->price->poa == 1 ? true : false,
                'monthlyLease' => $v->price->leasePrice,
                'monthlyLeaseCurrency' => $v->price->leaseCurrency ? $v->price->leaseCurrency : 'GBP',
            ],
            'images' => $v->images()->orderBy('position','asc')->get(),
            'engineInfo' => $v->engineInfo,
            'specifications' => $v->specifications,
            'videoLinks' => $v->videoLinks,
            'condition' => $v->condition,
            'history' => $v->history,
            'description' => $v->description->description,
            'referenceNumber' => $v->description->referenceNumber,
            'features' => json_decode($v->features->features),
            'soldStatus' => $v->soldStatus,
            'autoTraderWebYes' => $v->autoTraderWebYes,
            'myWebYes' => $v->myWebYes,
            'featured' => $v->featured,
            'latest' => $v->latest,
        ];
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function create($typeId)
    {
        $this->authorize('create', Vehicle::class);

        $type = Type::where('type', $typeId)->first();
        $vehicleData = $this->getVehicleData($type);
        $vehicle = $this->getVehicleJson($type, null);
        return view('admin.vehicle.create', ['type' => $type, 'vehicle' => $vehicle, 'vehicleData' => $vehicleData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function store(Request $request)
    {
        $this->authorize('create', Vehicle::class);

        try {
            DB::beginTransaction();
            $vehicle_id = $this->updateDetails($request);
            $this->updateSpecifications($request, $vehicle_id);
            $this->updateEngineInfo($request, $vehicle_id);
            $this->updateCondition($request, $vehicle_id);
            $this->updateHistory($request, $vehicle_id);
            $this->updateDescription($request->description,$request->referenceNumber, $vehicle_id);
            $this->updateFeatures($request->features, $vehicle_id);
            DB::commit();

            $v = Vehicle::findOrFail($vehicle_id);
            $vehicle = $this->getVehicleJson($v->type, $v);

            return response(['vehicle' => $vehicle, 'url' => route('admin.vehicle.show', ['id' => $vehicle_id])]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', Vehicle::class);

        try {
            $v = Vehicle::findOrFail($id);
            $vehicleData = $this->getVehicleData($v->type);
            $vehicle = $this->getVehicleJson($v->type, $v);
            return view('admin.vehicle.show', ['id'=> $id,'type' => $v->type, 'vehicle' => $vehicle, 'vehicleData' => $vehicleData]);

        } catch (ModelNotFoundException $e) {
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        } catch (\Exception $e) {
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return integer
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateDetails(Request $request)
    {
        $this->authorize('update', Vehicle::class);

        $details = (object)$request->details;

        if ($request->action === 'STORE' && $details->vehicle_id == null) {
            $vehicle = new Vehicle();
        } else {
            $vehicle = Vehicle::findOrFail($details->vehicle_id);
        }
        $vehicle->type_id = $details->type_id;
        $vehicle->category = $details->category;
        $vehicle->subcategory = $details->subcategory;

        if($details->make){
            $make = Make::find($details->make);
            if(!$make){
                $make = Make::firstOrCreate(['type_id' => $details->type_id,'make' => strtoupper($details->make)]);
            }
            $vehicle->make = $make->id;
        }

        if($details->model){
            $model = VehicleModel::find($details->model);
            if(!$model){
                $model = VehicleModel::firstOrCreate(['make_id' => $make->id,'model' => strtoupper($details->model)]);
            }
            $vehicle->model = $model->id;
        }

        $vehicle->derivative = $details->derivative;
        $vehicle->soldStatus = $request->soldStatus;
        $vehicle->autoTraderWebYes = $request->autoTraderWebYes == 0 ? 0 : 1;
        $vehicle->myWebYes = $request->myWebYes == 0 ? 0 : 1;
        $vehicle->featured = $request->featured == 0 ? 0 : 1;
        $vehicle->latest = $request->latest == 0 ? 0 : 1;
        $vehicle->updated_at = Carbon::now();
        $vehicle->save();

        $price = VehiclePriceDetail::where('vehicle_id', $vehicle->id)->first();
        if (!$price) {
            $price = new VehiclePriceDetail();
            $price->vehicle_id = $vehicle->id;
        }

        $price->saleprice = $details->salePrice;
        $price->priceCurrency = $details->salePriceCurrency;
        $price->poa = $details->poa;
        $price->vat = $details->vat;
        $price->leasePrice = $details->monthlyLease;
        $price->leaseCurrency = $details->monthlyLeaseCurrency;
        $price->soldStatus = false;
        $price->save();

        $registration = VehicleRegistration::where('vehicle_id', $vehicle->id)->first();
        if (!$registration) {
            $registration = new VehicleRegistration();
            $registration->vehicle_id = $vehicle->id;
        }

        $registration->vehicleNew = $details->vehicleNew;
        $registration->registrationMonth = $details->registrationYear;

        // update or create Registration Plate
        if($details->registrationPlate){
            $registrationPlate = RegistrationPlate::where('plate',$details->registrationPlate)->first();
            if(!$registrationPlate){
                $registrationPlate = RegistrationPlate::firstOrCreate(['plate' => $details->registrationPlate]);
            }
            $registration->registrationPlate = $registrationPlate->plate;
        }

        $registration->registrationYear = $details->registrationYear;
        $registration->serialNumber = $details->serialNumber;
        $registration->save();

        $vDetails = VehicleDetail::where('vehicle_id', $vehicle->id)->first();
        if (!$vDetails) {
            $vDetails = new VehicleDetail();
            $vDetails->vehicle_id = $vehicle->id;
        }

        $vDetails->axleDrive = $details->axleDrive;

        // update or create Body Type
        if($details->bodyType){
            $bodyType = BodyType::where('type_id',$details->type_id)->where('body_type',$details->bodyType)->first();
            if(!$bodyType){
                $bodyType = BodyType::firstOrCreate(['type_id' => $details->type_id,'body_type' => ucwords(strtolower($details->bodyType))]);
            }
            $vDetails->bodyType = $bodyType->body_type;
        }

        // update or create Cab Type
        if ($details->cabType){
            $cabType = CabType::where('cab_type',$details->cabType)->first();
            if(!$cabType){
                $cabType = CabType::firstOrCreate(['cab_type' => ucwords(strtolower($details->cabType))]);
            }
            $vDetails->cabType = $cabType->cab_type;
        }

        $vDetails->isDriverLeftHandSide = $details->isLeftDriverPosition;
        $vDetails->manufacturerYear = $details->manufacturerYear;
        $vDetails->mileage = $details->mileage;
        $vDetails->mileageUnit = $details->mileageUnit;
        $vDetails->save();

        return $vehicle->id;
    }

    /**
     * Update Vehicle Engine Info
     * @param  \Illuminate\Http\Request $request
     * @return void
     */

    public function updateEngineInfo(Request $request, $vehicle_id)
    {

        $engineInfo = (object)$request->engineInfo;
        $vEngineInfo = VehicleEngineInfo::where('vehicle_id', $vehicle_id)->first();

        if ($vEngineInfo == null) {
            $vEngineInfo = new VehicleEngineInfo();
            $vEngineInfo->vehicle_id = $vehicle_id;
        }

        $vEngineInfo->fuelType = $engineInfo->fuelType;
        $vEngineInfo->emissions = $engineInfo->emissions;
        $vEngineInfo->power = $engineInfo->power;
        $vEngineInfo->powerUnit = $engineInfo->powerUnit;
        $vEngineInfo->capacity = $engineInfo->capacity;
        $vEngineInfo->capacityUnit = $engineInfo->capacityUnit;
        $vEngineInfo->transmission = $engineInfo->transmission;
        $vEngineInfo->save();

    }

    /**
     * Update Vehicle Specifications
     * @param  \Illuminate\Http\Request $request
     * @return void
     */

    public function updateSpecifications(Request $request, $vehicle_id)
    {

        $specifications = (object)$request->specifications;
        $vSpecification = VehicleSpecification::where('vehicle_id', $vehicle_id)->first();

        if ($vSpecification == null) {
            $vSpecification = new VehicleSpecification();
            $vSpecification->vehicle_id = $vehicle_id;
        }

        $vSpecification->numberOfSeats = $specifications->numberOfSeats;
        $vSpecification->colour = $specifications->colour;
        $vSpecification->grossWeight = $specifications->grossWeight;
        $vSpecification->weightUnit = $specifications->weightUnit;
        $vSpecification->volume = $specifications->volume;
        $vSpecification->volumeUnit = $specifications->volumeUnit;
        $vSpecification->internalHeight = $specifications->internalHeight;
        $vSpecification->internalWidth = $specifications->internalWidth;
        $vSpecification->internalLength = $specifications->internalLength;
        $vSpecification->internalUnit = $specifications->internalUnit;
        $vSpecification->externalWidth = $specifications->externalWidth;
        $vSpecification->externalHeight = $specifications->externalHeight;
        $vSpecification->externalLength = $specifications->externalLength;
        $vSpecification->externalUnit = $specifications->externalUnit;
        $vSpecification->liftingCapacity = $specifications->liftingCapacity;
        $vSpecification->liftingCapacityUnit = $specifications->liftingCapacityUnit;
        $vSpecification->maxPayload = $specifications->maxPayload;
        $vSpecification->maximumHeight = $specifications->maximumHeight;
        $vSpecification->maximumHeightUnit = $specifications->maximumHeightUnit;
        $vSpecification->maximumReachUnit = $specifications->maximumReachUnit;
        $vSpecification->numberOfDoors = $specifications->numberOfDoors;
        $vSpecification->numberOfSeats = $specifications->numberOfSeats;
        $vSpecification->operatingType = $specifications->operatingType;
        $vSpecification->operatingWidth = $specifications->operatingWidth;
        $vSpecification->operatingWidthUnit = $specifications->operatingWidthUnit;
        $vSpecification->payloadUnit = $specifications->payloadUnit;
        $vSpecification->speed = $specifications->speed;
        $vSpecification->speedUnit = $specifications->speedUnit;
        $vSpecification->trailerAxis = $specifications->trailerAxis;
        $vSpecification->trailerWeight = $specifications->trailerWeight;
        $vSpecification->trailerWeightUnit = $specifications->trailerWeightUnit;
        $vSpecification->wheelbase = $specifications->wheelbase;
        $vSpecification->wheelbaseType = $specifications->wheelbaseType;
        $vSpecification->wheelbaseUnit = $specifications->wheelbaseUnit;

        $vSpecification->save();
    }

    /**
     * Upload Multiple Vehicle Photos
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function uploadPhotos(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|mimes:jpeg,bmp,png|max:5120'
        ]);
        try {
            $vCount = VehicleImage::where('vehicle_id', $request->vehicle_id)->count();
            $defaultCount = VehicleImage::where('vehicle_id', $request->vehicle_id)->where('default', 1)->count();
            foreach ($request->photos as $photo) {
                $vImage = new VehicleImage();
                $vImage->vehicle_id = $request->vehicle_id;
                $path = Storage::disk('public')->putFile('photos', $photo, 'public');
                $vImage->image = $path;
                if ($defaultCount == 0) {
                    $vImage->default = 1;
                    $defaultCount++;
                }
                $vImage->position = $vCount++;
                $vImage->save();
                $this->resizeImage($path);
            }
            $images = VehicleImage::where('vehicle_id', $request->vehicle_id)->orderBy('position','asc')->get();
            return response(['images' => $images]);
        } catch (\Exception $e) {
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);

        }
    }

    /**
     * Set Default Photo Or Delete A Photo
     *
     */

    function updatePhotos(Request $request)
    {
        $request->validate([
            'photo_id' => 'required',
            'vehicle_id' => 'required',
            'action' => 'required'
        ]);

        try {
            $image = VehicleImage::find($request->photo_id);
            if ($request->action == 'DELETE') {
                $path = $image->image;
                $image->delete();
                Storage::disk('public')->delete($path);
                Storage::disk('public')->delete('small/'.$path);
            } else {
                VehicleImage::where('vehicle_id', $request->vehicle_id)->update(['default' => 0]);
                $image->default = 1;
                $image->save();
            }
            $images = VehicleImage::where('vehicle_id', $request->vehicle_id)->orderBy('position','asc')->get();
            return response(['images' => $images]);
        } catch (\Exception $e) {
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Update Photo position
     *
     */

    function updatePhotosPosition(Request $request)
    {
        $request->validate([
            'photos' => 'required',
            'vehicle_id' => 'required',
            'action' => 'required'
        ]);

        try {
            foreach ($request->photos as $photo){
                VehicleImage::where('id', $photo['id'])->update(['position' => $photo['position']]);
            }
            return response(['success' => "Updated the position successfully"]);
        } catch (\Exception $e) {
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        }
    }

    /**
     * Update YouTube Link for Vehicle
     *
     */

    function updateYoutube(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required',
            'youtubeLink' => 'required'
        ]);

        try {
            $vehicle = VehicleLink::where('vehicle_id', $request->vehicle_id)->first();
            if (!$vehicle) {
                $vehicle = new VehicleLink();
                $vehicle->vehicle_id = $request->vehicle_id;
            }
            $vehicle->link = $request->youtubeLink;
            $vehicle->type = $request->type;
            $vehicle->save();

            return response(['success' => 'Updated Successfully']);
        } catch (\Exception $e) {
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        }

    }

    /**
     * Update Vehicle Condition
     * @param  \Illuminate\Http\Request $request
     * @return void
     */

    public function updateCondition(Request $request, $vehicle_id)
    {

        $condition = (object)$request->condition;
        if($request->condition == null){
            return;
        }
        $vCondition = VehicleCondition::where('vehicle_id', $vehicle_id)->first();

        if ($vCondition == null) {
            $vCondition = new VehicleCondition();
            $vCondition->vehicle_id = $vehicle_id;
        }

        $vCondition->bodyCondition = $condition->bodyCondition;
        $vCondition->interiorCondition = $condition->interiorCondition;
        $vCondition->overallCondition = $condition->overallCondition;
        $vCondition->tyreCondition = $condition->tyreCondition;
        $vCondition->save();

    }

    /**
     * Update Vehicle History
     * @param  \Illuminate\Http\Request $request
     * @return void
     */

    public function updateHistory(Request $request, $vehicle_id)
    {

        $history = (object)$request->history;
        if($request->history == null){
            return;
        }
        $vhistory = VehicleHistory::where('vehicle_id', $vehicle_id)->first();

        if ($vhistory == null) {
            $vhistory = new VehicleHistory();
            $vhistory->vehicle_id = $vehicle_id;
        }

        $vhistory->hoursUsed = $history->hoursUsed;
        $vhistory->lastInspectionDate = $history->lastInspectionDate;
        $vhistory->motDate = $history->motDate;
        $vhistory->previousOwner = $history->previousOwner;
        $vhistory->serviceHistory = $history->serviceHistory;
        $vhistory->save();

    }

    /**
     * Update Vehicle Description
     * @param $desp
     * @param $referenceNumber
     * @param $vehicle_id
     * @return void
     */

    public function updateDescription($desp,$referenceNumber, $vehicle_id)
    {


        $description = VehicleDescription::where('vehicle_id', $vehicle_id)->first();

        if ($description == null) {
            $description = new VehicleDescription();
            $description->vehicle_id = $vehicle_id;
        }

        $description->description = $desp;
        $description->referenceNumber = $referenceNumber;
        $description->save();

    }

    /**
     * Update Vehicle Description
     * @param $newFeatures
     * @param $vehicle_id
     * @return void
     */

    public function updateFeatures($newFeatures, $vehicle_id)
    {
        $features = VehicleFeature::where('vehicle_id', $vehicle_id)->first();

        if ($features == null) {
            $features = new VehicleFeature();
            $features->vehicle_id = $vehicle_id;
        }

        $features->features = json_encode($newFeatures);
        $features->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            Vehicle::findOrFail($request->vehicle_id);
            $this->authorize('delete', Vehicle::class);
            $images = VehicleImage::where('vehicle_id',$request->vehicle_id)->get();
            foreach ($images as $image){
                Storage::disk('public')->delete($image->image);
                Storage::disk('public')->delete('small/'.$image->image);
            }
            Vehicle::destroy($request->vehicle_id);
            VehicleCondition::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleDescription::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleDetail::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleEngineInfo::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleFeature::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleHistory::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleImage::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleLink::where('vehicle_id',$request->vehicle_id)->delete();
            VehiclePriceDetail::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleRegistration::where('vehicle_id',$request->vehicle_id)->delete();
            VehicleSpecification::where('vehicle_id',$request->vehicle_id)->delete();

            if ($request->isAjax){
                return response(['message' => 'Delete Successfully']);
            }
            return redirect()->route('admin.vehicles.index')->with('status', 'Successfully Deleted Vehicle #'.$request->vehicle_id);

        } catch (ModelNotFoundException $e) {
            if ($request->isAjax){
                return response(['error' => true, 'errorMessage' => "Vehicle Not Found. Please Refresh or Contact Administrator"]);
            }
            return redirect()->back()->with('error',$e->getMessage());
        }
        catch (\Exception $ex) {
            if ($request->isAjax){
                return response(['error' => true, 'errorMessage' => $ex->getMessage()]);
            }
            return redirect()->back()->with('error',$ex->getMessage());
        }
    }

    /**
     *  Update Vehicle Attributes
     *  Attributes : Stock Status, Featured, Latest
     * */
    public function updateStatus(Request $request)
    {
        try{
            $this->authorize('update', Vehicle::class);
            $vehicle = Vehicle::findOrFail($request->id);
            if($request->soldStatus){
                if($vehicle->soldStatus == 'In Stock'){
                    $vehicle->autoTraderWebYes = 0;
                    $vehicle->myWebYes = 0;
                }
                $vehicle->soldStatus = $vehicle->soldStatus == 'Sold' ? 'In Stock' : 'Sold';
            }
            if($request->reservedStatus){
                $vehicle->soldStatus = 'Reserved';
            }
            if($request->featured){
                $vehicle->featured = $vehicle->featured == 1 ? 0 : 1;
            }
            if($request->latest){
                $vehicle->latest = $vehicle->latest == 1 ? 0 : 1;
            }
            if($request->autoTraderWebYes){
                $vehicle->autoTraderWebYes = $vehicle->autoTraderWebYes == 1 ? 0 : 1;
            }
            if($request->myWebYes){
                $vehicle->myWebYes = $vehicle->myWebYes == 1 ? 0 : 1;
            }
            $vehicle->save();

            if($request->poa){
                $vehiclePrice = VehiclePriceDetail::where('vehicle_id',$request->id)->first();
                $vehiclePrice->poa = $vehiclePrice->poa == 1 ? 0 : 1;
                $vehiclePrice->save();
            }

            return response(['message' => 'Updated Successfully']);

        }catch (ModelNotFoundException $e){
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        }catch (\Exception $ex){
            return response(['error' => true, 'errorMessage' => $ex->getMessage()]);
        }

    }


    /**
     *  Update Stock
     *  Attributes : Stock Status, Featured, Latest
     * */
    public function updateStock(Request $request)
    {
        try{
            $this->authorize('update', Vehicle::class);
            $action = [];
            switch ($request->action){
                case 'sold':
                    $action = ['soldStatus' => 'Sold','myWebYes' => 0,'autoTraderWebYes' => 0];
                    break;
                case 'inStock':
                    $action = ['soldStatus' => 'In Stock'];
                    break;
                case 'poa':
                    $priceAction = ['poa' => 1];
                    break;
                case 'removePoa':
                    $priceAction = ['poa' => 0];
                    break;
                case 'featured':
                    $action = ['featured' => 1];
                    break;
                case 'latest':
                    $action = ['latest' => 1];
                    break;
                case 'delete':
                    $this->authorize('delete', Vehicle::class);
                    Vehicle::whereIn('id',$request->ids)->delete();
                    VehicleCondition::whereIn('id',$request->ids)->delete();
                    VehicleDescription::whereIn('id',$request->ids)->delete();
                    VehicleDetail::whereIn('id',$request->ids)->delete();
                    VehicleEngineInfo::whereIn('id',$request->ids)->delete();
                    VehicleFeature::whereIn('id',$request->ids)->delete();
                    VehicleHistory::whereIn('id',$request->ids)->delete();
                    VehicleImage::whereIn('id',$request->ids)->delete();
                    VehicleLink::whereIn('id',$request->ids)->delete();
                    VehiclePriceDetail::whereIn('id',$request->ids)->delete();
                    VehicleRegistration::whereIn('id',$request->ids)->delete();
                    VehicleSpecification::whereIn('id',$request->ids)->delete();
                    return response(['message' => 'Updated Successfully']);
                    break;
                case 'visibleOnWeb':
                    $action = ['myWebYes' => 1];
                    break;
                case 'visibleOnAutoTrader':
                    $action = ['autoTraderWebYes' => 1];
                    break;
                case 'hideOnWeb':
                    $action = ['myWebYes' => 0];
                    break;
                case 'hideOnAutoTrader':
                    $action = ['autoTraderWebYes' => 0];
                    break;
            }
            if(count($action) > 0){
                Vehicle::whereIn('id',$request->ids)->update($action);
                return response(['message' => 'Updated Successfully']);
            }

            if(count($priceAction) > 0){
                VehiclePriceDetail::whereIn('vehicle_id',$request->ids)->update($priceAction);
                return response(['message' => 'Updated Successfully']);
            }

            return response(['message' => 'Error occurred while updating the system']);

        }catch (ModelNotFoundException $e){
            return response(['error' => true, 'errorMessage' => $e->getMessage()]);
        }catch (\Exception $ex){
            return response(['error' => true, 'errorMessage' => $ex->getMessage()]);
        }
    }

    function resizeImage($imageName){
        $img = Image::make(storage_path('app/public/'.$imageName))->resize(100, null,function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(storage_path('app/public/small/'.$imageName));
    }

    function ResizeAllImages(){
        try{
            $files = Storage::disk('public')->allFiles('small/photos');
            foreach($files as $file){
                Storage::disk('public')->delete($file);
            }
            $vehicleImages = VehicleImage::get();
            foreach ($vehicleImages as $image){
                $img = Image::make(storage_path('app/public/'.$image->image))->resize(100, null,function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(storage_path('app/public/small/'.$image->image));
            }
            return "Success";
        }catch (\Exception $e){
            return $e->getMessage();
        }

    }
}
