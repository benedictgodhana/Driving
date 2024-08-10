<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitutionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert predefined institutions
        DB::table('institutions')->insert([
            ['name' => 'University of Nairobi', 'location_id' => 1], // Assuming location_id 1 is Nairobi
            ['name' => 'Kenyatta University', 'location_id' => 1],
            ['name' => 'Moi University', 'location_id' => 5], // Assuming location_id 5 is Eldoret
            ['name' => 'Jomo Kenyatta University of Agriculture and Technology', 'location_id' => 1],
            ['name' => 'Egerton University', 'location_id' => 4], // Assuming location_id 4 is Nakuru
            ['name' => 'Maseno University', 'location_id' => 3], // Assuming location_id 3 is Kisumu
            ['name' => 'Technical University of Mombasa', 'location_id' => 2], // Assuming location_id 2 is Mombasa
            ['name' => 'Kisii University', 'location_id' => 7], // Assuming location_id 7 is Kisii
            // Add more institutions as needed
        ]);
    }
}
