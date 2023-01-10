<?php

namespace Database\Seeders;

use App\Models\Via;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vias')->insert([
            ["name"=>"Endovenosa"],
            ["name"=>"Intramuscular"],
            ["name"=>"Intravenosa"],
            ["name"=>"Oral"],
            ["name"=>"Subcutanea"],
            ["name"=>"Topica"],
            ["name"=>"Vaginal"],
            ["name"=>"Rectal"],
        ]);
    }
}
