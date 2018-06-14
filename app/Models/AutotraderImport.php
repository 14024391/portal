<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use DateTime;
class AutotraderImport extends Model
{
    protected $table = 'autotrader_imports';


    public function getCreatedAtAttribute($value)
    {
        return $this->convertTime($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->convertTime($value);
    }

    public function getProcessedAtAttribute($value)
    {
        return $this->convertTime($value);
    }

    public function convertTime($value){
        if($value){
            $value = Carbon::createFromFormat('Y-d-m H:i:s', $value,'UTC')
                ->timezone('Europe/London')
                ->format('d-m-Y H:i');
            return $value;
        }
        return null;
    }
}
