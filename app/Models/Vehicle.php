<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    function type(){
        return $this->belongsTo('App\Models\Type');
    }

    function registration(){
        return $this->hasOne('App\Models\VehicleRegistration');
    }

    function details(){
        return $this->hasOne('App\Models\VehicleDetail');
    }

    function price(){
        return $this->hasOne('App\Models\VehiclePriceDetail');
    }

    function images(){
        return $this->hasMany('App\Models\VehicleImage');
    }

    function videoLinks(){
        return $this->hasOne('App\Models\VehicleLink');
    }

    function specifications(){
        return $this->hasOne('App\Models\VehicleSpecification');
    }

    function engineInfo(){
        return $this->hasOne('App\Models\VehicleEngineInfo');
    }

    function condition(){
        return $this->hasOne('App\Models\VehicleCondition');
    }

    function history(){
        return $this->hasOne('App\Models\VehicleHistory');
    }

    function description(){
        return $this->hasOne('App\Models\VehicleDescription');
    }

    function features(){
        return $this->hasOne('App\Models\VehicleFeature');
    }

}
