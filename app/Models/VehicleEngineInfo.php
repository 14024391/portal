<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleEngineInfo extends Model
{
    use SoftDeletes;

    protected $table = 'vehicle_engine_info';
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
