<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            ["name"=>""],
            ["name"=>"Ampolla"],
            ["name"=>"Aplicaion"],
            ["name"=>"Capsula"],
            ["name"=>"Gotas"],
            ["name"=>"Inhalacion"],
            ["name"=>"Inyeccion"],
            ["name"=>"Jarabe"],
            ["name"=>"Pastilla"],
            ["name"=>"Pomada"],
            ["name"=>"Supositorio"],
            ["name"=>"Tableta"],
            ["name"=>"Tubo"],
        ]);
    }
}
