<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert predefined locations in Kenya
        DB::table('locations')->insert([
            ['name' => 'Nairobi'],
            ['name' => 'Mombasa'],
            ['name' => 'Kisumu'],
            ['name' => 'Nakuru'],
            ['name' => 'Eldoret'],
            ['name' => 'Kericho'],
            ['name' => 'Thika'],
            ['name' => 'Meru'],
            ['name' => 'Garissa'],
            ['name' => 'Malindi'],
            // Add more locations as needed
        ]);
    }
}
