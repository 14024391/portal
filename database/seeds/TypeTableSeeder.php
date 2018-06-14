<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("types")->insert([
            ['type' => 'Truck'],
            ['type' => 'Plant'],
            ['type' => 'Farm'],
            ['type' => 'Car'],
            ['type' => 'Van']
        ]);
    }
}
