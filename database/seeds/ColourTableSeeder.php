<?php

use Illuminate\Database\Seeder;

class ColourTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("colours")->insert([
            ["colour" => "Beige"],
            ["colour" => "Black"],
            ["colour" => "Blue"],
            ["colour" => "Bronze"],
            ["colour" => "Brown"],
            ["colour" => "Burgundy"],
            ["colour" => "Gold"],
            ["colour" => "Green"],
            ["colour" => "Grey"],
            ["colour" => "Indigo"],
            ["colour" => "Magenta"],
            ["colour" => "Maroon"],
            ["colour" => "Multicolour"],
            ["colour" => "Navy"],
            ["colour" => "Orange"],
            ["colour" => "Pink"],
            ["colour" => "Purple"],
            ["colour" => "Red"],
            ["colour" => "Silver"],
            ["colour" => "Turquoise"],
            ["colour" => "White"],
            ["colour" => "Yellow"]
        ]);
    }
}
