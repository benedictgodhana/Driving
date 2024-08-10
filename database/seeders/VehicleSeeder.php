<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample data for vehicles
        $vehicles = [
            [
                'reg_no' => 'KAA 123A',
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reg_no' => 'KBB 456B',
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reg_no' => 'KCC 789C',
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert data into the vehicles table
        DB::table('vehicles')->insert($vehicles);
    }
}

