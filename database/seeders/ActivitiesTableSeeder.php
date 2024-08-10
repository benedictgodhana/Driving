<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity; // Ensure you have the correct namespace for the Activity model

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for activities
        $activities = [
            [
                'name' => 'Community Service',
                'institution_id' => 1, // Ensure this ID exists in your institutions table
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Internship',
                'institution_id' => 2, // Ensure this ID exists in your institutions table
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Research Project',
                'institution_id' => 1, // Example ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more activities as needed
        ];

        // Insert data into the activities table
        Activity::insert($activities);
    }
}
