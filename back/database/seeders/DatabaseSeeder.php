<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
//use App\Models\Unit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            HospitalSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            PatientSeeder::class,
            ProductSeeder::class,
            MedicineSeeder::class,
            NumberSeeder::class,
            TimeSeeder::class,
            UnitSeeder::class,
            ViaSeeder::class,
        ]);
    }
}
