<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('patients')->insert([
            'name'=>"Juan Esteban",
            'lastname'=>"Lopez Calee",
            'sex'=>"MASCULINO",
            'phone'=>"69754564",
            'email'=>"juan@gmail.com",
            'address'=>"avenida dn",
            'birthday'=>"2000-01-01",
            'hospital_id'=>"1"
        ]);

    }
}
