<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehiclePriceDetail extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    protected $dates = ['deleted_at'];

    public function getSalepriceAttribute($value)
    {
        return floor($value);
    }

    public function getLeasePriceAttribute($value)
    {
        return floor($value);
    }
}
