<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("categories")->insert([
            [ "id" => 1, 'type_id' => 1, 'category' => 'Rigid Trucks'],
            [ "id" => 2, 'type_id' => 1, 'category' => 'Tractor Units'],
            [ "id" => 3, 'type_id' => 1, 'category' => 'Trailers'],
            [ "id" => 4, 'type_id' => 2, 'category' => "Aerial Platforms"],
            [ "id" => 5, 'type_id' => 2, 'category' => "Attachments"],
            [ "id" => 6, 'type_id' => 2, 'category' => "Buildings and Storage"],
            [ "id" => 7, 'type_id' => 2, 'category' => "Compaction Machines"],
            [ "id" => 8, 'type_id' => 2, 'category' => "Compressors"],
            [ "id" => 9, 'type_id' => 2, 'category' => "Concrete"],
            [ "id" => 10, 'type_id' => 2, 'category' => "Conveyor Belts"],
            [ "id" => 11, 'type_id' => 2, 'category' => "Cranes"],
            [ "id" => 12, 'type_id' => 2, 'category' => "Dozers"],
            [ "id" => 13, 'type_id' => 2, 'category' => "Dumpers"],
            [ "id" => 14, 'type_id' => 2, 'category' => "Excavators"],
            [ "id" => 15, 'type_id' => 2, 'category' => "Forklifts"],
            [ "id" => 16, 'type_id' => 2, 'category' => "Generators"],
            [ "id" => 17, 'type_id' => 2, 'category' => "Graders"],
            [ "id" => 18, 'type_id' => 2, 'category' => "Loaders"],
            [ "id" => 19, 'type_id' => 2, 'category' => "Mining and Quarry Equipment"],
            [ "id" => 20, 'type_id' => 2, 'category' => "Road Construction Equipment"],
            [ "id" => 21, 'type_id' => 2, 'category' => "Road and Paving Machine"],
            [ "id" => 22, 'type_id' => 2, 'category' => "Scrappers"],
            [ "id" => 23, 'type_id' => 2, 'category' => "Telehandlers"],
            [ "id" => 24, 'type_id' => 2, 'category' => "Tools and Equipment"],
            [ "id" => 25, 'type_id' => 2, 'category' => "Trailers"],
            [ "id" => 26, 'type_id' => 2, 'category' => "Waste and Recycling"],
            [ "id" => 27, 'type_id' => 3, "category" => "4WD Vehicles"],
            [ "id" => 28, 'type_id' => 3, "category" => "ATVs"],
            [ "id" => 29, 'type_id' => 3, "category" => "Attachments"],
            [ "id" => 30, 'type_id' => 3, "category" => "Forage and Hay"],
            [ "id" => 31, 'type_id' => 3, "category" => "Forestry and Hedging"],
            [ "id" => 32, 'type_id' => 3, "category" => "Ground Care Equipment"],
            [ "id" => 33, 'type_id' => 3, "category" => "Harvesters"],
            [ "id" => 34, 'type_id' => 3, "category" => "Livestock Equipment"],
            [ "id" => 35, 'type_id' => 3, "category" => "Loaders and Excavators"],
            [ "id" => 36, 'type_id' => 3, "category" => "Sowing and Planting"],
            [ "id" => 37, 'type_id' => 3, "category" => "Spreaders and Sprayers"],
            [ "id" => 38, 'type_id' => 3, "category" => "Storage and Buildings"],
            [ "id" => 39, 'type_id' => 3, "category" => "Tillage"],
            [ "id" => 40, 'type_id' => 3, "category" => "Tractors"],
            [ "id" => 41, 'type_id' => 3, "category" => "Trailers"]
        ]);
    }
}
