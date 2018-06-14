<?php

use Illuminate\Database\Seeder;

class EmissionClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("emission_classes")->insert([
            ["emission_class" => "Euro 1"],
            ["emission_class" => "Euro 2"],
            ["emission_class" => "Euro 3"],
            ["emission_class" => "Euro 4"],
            ["emission_class" => "Euro 5"],
            ["emission_class" => "Euro 6"]
        ]);
    }
}
