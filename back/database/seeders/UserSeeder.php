<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'ADIMER PAUL CHAMBI AJATA',
            'email' => 'adimer101@gmail.com',
            'password' => bcrypt('123456'),
            'sex'=>"MASCUINO",
            'specialty'=>"MEDICO GENERAL",
            'ci'=>"7336199",
            'hospital_id'=>"1",
        ]);
    }
}
