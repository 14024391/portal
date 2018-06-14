<?php

namespace App\Http\Controllers;

use App\Http\Resources\BodyTypeResource;
use App\Http\Resources\CabTypeResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ColourResource;
use App\Http\Resources\EmissionClassResource;
use App\Http\Resources\FuelTypeResource;
use App\Http\Resources\MakeResource;
use App\Http\Resources\RegistrationPlateResource;
use App\Models\BodyType;
use App\Models\CabType;
use App\Models\Category;
use App\Models\Colour;
use App\Models\EmissionClass;
use App\Models\FuelType;
use App\Models\Make;
use App\Models\Model;
use App\Models\RegistrationPlate;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /*
     * Get category by Vehicle Type ID
     * param : vehicle Type Id
     * */
    public function categories($id){
        return new CategoryResource(Category::where('type_id',$id)->get());
    }

    /*
     * Get Vehicle Make
     * */
    function makes(){
        return new MakeResource(Make::get());
    }

    /*
     * Get Vehicle Models
     * @params make
     * */
    function models($id){
        return new MakeResource(VehicleModel::where('make_id',$id)->get());
    }

    /*
     * Get FuelTypes
     * */
    function fuelTypes(){
        return new FuelTypeResource(FuelType::get());
    }

    /*
     * Get EmissionClasses
     * */
    function emissionClasses(){
        return new EmissionClassResource(EmissionClass::get());
    }

    /*
     * Get Colors
     * */
    function colours(){
        return new ColourResource(Colour::get());
    }

    /*
     * Api Registration Plates
     * */

    function registrationPlates(){
        return new RegistrationPlateResource(RegistrationPlate::get());
    }

    /*
     * Api Body Types
     * */

    function bodyTypes($categoryId){
        return new BodyTypeResource(BodyType::where('category_id',$categoryId)->get());
    }

    /*
     * Api Body Types
     * */

    function axleDrives($type){

    }

    /*
     * Api Body Types
     * */

    function cabTypes($categoryId){
        return new CabTypeResource(CabType::where('category_id',$categoryId)->get());
    }
}
