<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['name' => 'timezone', 'value' => 'Europe/London','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'logo_light', 'value' => 'logo_light.png','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'dvla_enabled', 'value' => 1,'created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'dvla_api_key', 'value' => '907DE93B-E8C8-43BB-BD9C-DD9119A18746','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'dvla_daily_limit', 'value' => '50','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'dvla_daily_usages', 'value' => '0','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'autotrader_supplier_name', 'value' => 'amcommercials','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'autotrader_client_id', 'value' => '12928','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'autotrader_ftp_host', 'value' => '','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'autotrader_ftp_port', 'value' => '22','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'autotrader_ftp_username', 'value' => '','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()],
            ['name' => 'autotrader_ftp_password', 'value' => '','created_at' => \Carbon\Carbon::now(),'updated_at' => \Carbon\Carbon::now()]
        ]);

    }
}
