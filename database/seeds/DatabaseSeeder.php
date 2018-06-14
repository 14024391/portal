<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingsTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(TypeTableSeeder::class);
        $this->call(YearTableSeeder::class);
        $this->call(FuelTypeTableSeeder::class);
        $this->call(MakeTableSeeder::class);
        $this->call(EmissionClassTableSeeder::class);
        $this->call(ColourTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(SubCategoryTableSeeder::class);
        $this->call(ModelTableSeeder::class);
        $this->call(RegistrationPlateTableSeeder::class);
        $this->call(CabTypeTableSeeder::class);
        $this->call(BodyTypeTableSeeder::class);
        $this->call(FeaturesTableSeeder::class);
    }
}
