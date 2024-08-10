<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationsTableSeeder extends Seeder
{
    /**
     * Seed the stations table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stations')->insert([
            [
                'station_code' => 'ST001',
                'station_name' => 'Central Station',
                'station_phone_number' => '123-456-7890',
                'address' => '123 Main Street, Cityville',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'station_code' => 'ST002',
                'station_name' => 'North Station',
                'station_phone_number' => '987-654-3210',
                'address' => '456 North Avenue, Townsville',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more stations as needed
        ]);
    }
}
