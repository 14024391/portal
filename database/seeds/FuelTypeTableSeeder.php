<?php

use Illuminate\Database\Seeder;

class FuelTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fuel_types = array(
            array('type_id' => '1','fuel_type' => 'Bi Fuel'),
            array('type_id' => '1','fuel_type' => 'CNG'),
            array('type_id' => '1','fuel_type' => 'Diesel'),
            array('type_id' => '1','fuel_type' => 'Electric'),
            array('type_id' => '1','fuel_type' => 'Hybrid'),
            array('type_id' => '1','fuel_type' => 'Hydrogen'),
            array('type_id' => '1','fuel_type' => 'LNG'),
            array('type_id' => '1','fuel_type' => 'LPG'),
            array('type_id' => '1','fuel_type' => 'Other'),
            array('type_id' => '1','fuel_type' => 'Petrol'),
            array('type_id' => '2','fuel_type' => 'Bi Fuel'),
            array('type_id' => '2','fuel_type' => 'CNG'),
            array('type_id' => '2','fuel_type' => 'Diesel'),
            array('type_id' => '2','fuel_type' => 'Electric'),
            array('type_id' => '2','fuel_type' => 'Hybrid'),
            array('type_id' => '2','fuel_type' => 'Hydrogen'),
            array('type_id' => '2','fuel_type' => 'LNG'),
            array('type_id' => '2','fuel_type' => 'LPG'),
            array('type_id' => '2','fuel_type' => 'Other'),
            array('type_id' => '2','fuel_type' => 'Petrol'),
            array('type_id' => '3','fuel_type' => 'Bi Fuel'),
            array('type_id' => '3','fuel_type' => 'CNG'),
            array('type_id' => '3','fuel_type' => 'Diesel'),
            array('type_id' => '3','fuel_type' => 'Electric'),
            array('type_id' => '3','fuel_type' => 'Hybrid'),
            array('type_id' => '3','fuel_type' => 'Hydrogen'),
            array('type_id' => '3','fuel_type' => 'LNG'),
            array('type_id' => '3','fuel_type' => 'LPG'),
            array('type_id' => '3','fuel_type' => 'Other'),
            array('type_id' => '3','fuel_type' => 'Petrol'),
            array('type_id' => '4','fuel_type' => 'Bi Fuel'),
            array('type_id' => '4','fuel_type' => 'Diesel'),
            array('type_id' => '4','fuel_type' => 'Diesel Hybrid'),
            array('type_id' => '4','fuel_type' => 'Diesel Plug-in Hybrid'),
            array('type_id' => '4','fuel_type' => 'Electric'),
            array('type_id' => '4','fuel_type' => 'Hydrogen Hybrid'),
            array('type_id' => '4','fuel_type' => 'Petrol'),
            array('type_id' => '4','fuel_type' => 'Petrol Ethanol'),
            array('type_id' => '4','fuel_type' => 'Petrol Hybrid'),
            array('type_id' => '4','fuel_type' => 'Petrol Plug-in Hybrid'),
            array('type_id' => '5','fuel_type' => 'Bi Fuel'),
            array('type_id' => '5','fuel_type' => 'Diesel'),
            array('type_id' => '5','fuel_type' => 'Electric'),
            array('type_id' => '5','fuel_type' => 'Natural Gas'),
            array('type_id' => '5','fuel_type' => 'Petrol'),
            array('type_id' => '5','fuel_type' => 'Petrol Hybrid'),
            array('type_id' => '5','fuel_type' => 'Petrol Plug-in Hybrid')
        );
        DB::table("fuel_types")->insert($fuel_types);
    }
}
