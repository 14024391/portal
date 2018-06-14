<?php

use Illuminate\Database\Seeder;

class CabTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cab_types = array(
            array('cab_type' => 'Crew cab'),
            array('cab_type' => 'Day cab'),
            array('cab_type' => 'Double cab'),
            array('cab_type' => 'High sleeper cab'),
            array('cab_type' => 'Low access cab'),
            array('cab_type' => 'Short cab'),
            array('cab_type' => 'Sleeper cab'),
            array('cab_type' => 'Standard cab'),
            array('cab_type' => 'Transporter cab'),
            array('cab_type' => 'Top Sleeper'),
        );
        DB::table("cab_types")->insert($cab_types);
    }
}
