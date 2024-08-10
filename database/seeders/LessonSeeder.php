<?php
// database/seeders/LessonSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class LessonSeeder extends Seeder
{
    public function run()
    {
        // Sample data for lessons
        $lessons = [
            [
                'student_id' => 3, // Adjust according to your students table
                'instructor_id' => 1, // Adjust according to your instructors table
                'vehicle_id' => 4, // Adjust according to your vehicles table
                'datetime' => now()->addDays(1), // Example: tomorrow's date
                'attended' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'instructor_id' => 2,
                'vehicle_id' => 5,
                'datetime' => now()->addDays(2), // Example: day after tomorrow
                'attended' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 4,
                'instructor_id' => 3,
                'vehicle_id' => 6,
                'datetime' => now()->addDays(3), // Example: three days from now
                'attended' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];

        // Insert the sample data into the lessons table
        DB::table('lessons')->insert($lessons);
    }
}
