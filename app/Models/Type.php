<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    function vehicles(){
        return $this->hasMany('App\Models\Vehicle');
    }

    function makes(){
        return $this->hasMany('App\Models\Make');
    }
}
