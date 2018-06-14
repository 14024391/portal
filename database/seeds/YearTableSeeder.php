<?php

use Illuminate\Database\Seeder;

class YearTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $years = [];
        for($i = '1961'; $i <= 2019; $i++){
            array_push($years,['year' => $i]);
        }
        DB::table("years")->insert($years);
    }
}
