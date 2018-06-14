<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationPlate extends Model
{
    public $timestamps = false;
    protected $fillable = ['plate'];
}
