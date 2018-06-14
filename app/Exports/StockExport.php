<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vehicle;

class StockExport
{
    public function collection()
    {
        return Vehicle::all();
    }
}