<?php

namespace Database\Seeders;

use App\Models\Number;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('numbers')->insert([
            ['name' => '1'],
            ['name' => '2'],
            ['name' => '3'],
            ['name' => '4'],
            ['name' => '5'],
            ['name' => '6'],
            ['name' => '7'],
            ['name' => '8'],
            ['name' => '9'],
            ['name' => '10'],
            ['name' => '15'],
            ['name' => '20'],
            ['name' => '25'],
            ['name' => '30'],
            ['name' => '1/2'],
            ['name' => '1/4'],
            ['name' => '1/8'],
            ['name' => '60'],
            ['name' => '120'],
        ]);
    }
}
