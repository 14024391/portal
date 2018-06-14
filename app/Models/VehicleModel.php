<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $table = 'models';
    public $timestamps = false;
    protected $fillable = ['make_id','model'];
}
