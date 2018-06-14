<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->insert([
            ['name' => 'superadmin', 'description' => 'System Administrator'],
            ['name' => 'manager', 'description' => 'System Manager'],
            ['name' => 'stock', 'description' => 'Stock Admin'],
            ['name' => 'sales', 'description' => 'Sales Team'],
        ]);
    }
}
