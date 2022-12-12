<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hospitals')->insert([
            'nameHospital' => 'Hospital de la Mujer',
            'address' => 'Av. 6 de Diciembre 1000',
            'web' => 'www.hospitaldelamujer.com',
            'phone' => '0987654321',
        ]);
    }
}
