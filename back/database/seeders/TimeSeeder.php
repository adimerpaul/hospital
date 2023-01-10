<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('times')->insert([
            ["name"=>"2 veces al dia"],
            ["name"=>"3 veces al dia"],
            ["name"=>"Al acostarse"],
            ["name"=>"Al levantarse"],
            ["name"=>"Antes de las comidas"],
            ["name"=>"Despues de las comidas"],
            ["name"=>"Cada 4 horas"],
            ["name"=>"Cada 6 horas"],
            ["name"=>"Cada 8 horas"],
            ["name"=>"Cada 12 horas"],
            ["name"=>"Cada 24 horas"],
            ["name"=>"Cada 48 horas"],
            ["name"=>"Cada 72 horas"],
            ["name"=>"Cada 96 horas"],
            ["name"=>"Cada 120 horas"]
        ]);
    }
}
