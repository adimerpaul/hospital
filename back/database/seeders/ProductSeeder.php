<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'hospital_id'=>"1",
            'category_id'=>"1",
            'name'=>"paracetamol",
            'stock'=>"100",
            'stockMin'=>"100",
            'stockMax'=>"100",
            'photo'=>"avatar.png",
            'color'=>"#000000",
        ]);
    }
}
