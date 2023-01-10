<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('medicines')->insert([
            ['name' => 'paracetamol'],
            ['name' => 'ibuprofeno'],
            ['name' => 'aspirina'],
            ['name' => 'Dipirona'],
            ['name' => 'bicarbonato de sodio'],
            ['name' => 'fluoruro de sodio'],
            ['name' => 'misoprostol'],
            ['name' => 'omeprazol'],
            ['name' => 'ranitidina'],
            ['name' => 'atropina'],
            ['name' => 'butyl scopolamine'],
            ['name' => 'domperidona'],
            ['name' => 'metoclopramida'],
            ['name' => 'propinoxate'],
            ['name' => 'simethicone'],
            ['name' => 'ondansetrón'],
            ['name' => 'ondansetrón'],
            ['name' => 'bisacodilo'],
            ['name' => 'fiber'],
            ['name' => 'glicerina'],
            ['name' => 'lactulosa'],
            ['name' => 'sulfato de magnesio'],
            ['name' => 'mineral oil'],
            ['name' => 'carbón activado'],
            ['name' => 'loperamide'],
            ['name' => 'nistatina'],
            ['name' => 'glibenclamida'],
            ['name' => 'insulin glargine'],
            ['name' => 'insulin glulisina'],
            ['name' => 'metformina'],
            ['name' => 'metformina'],
            ['name' => 'insulina NPH (insulina de acción intermedia)'],
            ['name' => 'zinc crystalline insulin'],
            ['name' => 'aceite vitaminado (alimento)'],
            ['name' => 'ácido ascórbico'],
            ['name' => 'colecalciferol'],
            ['name' => 'multivitamins'],
            ['name' => 'piridoxina'],
            ['name' => 'retinol'],
            ['name' => 'tiamina'],
            ['name' => 'complejo vitamínico B'],
            ['name' => 'vitamina E'],
            ['name' => 'carbonato de calcio'],
            ['name' => 'cloruro de potasio'],
            ['name' => 'fluoruro de sodio'],
            ['name' => 'vitamin D'],
            ['name' => 'zinc'],
        ]);
    }
}
