<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    protected $fillable = ['type_id','make'];
    public $timestamps = false;
}
