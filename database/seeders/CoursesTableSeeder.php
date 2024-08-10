<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Seed the courses table.
     *
     * @return void
     */
    public function run()
    {
        // Insert courses
        DB::table('courses')->insert([
            [
                'course_code' => 'CS101',
                'course_name' => 'Introduction to Computer Science',
                'user_id' => 4, // Ensure this user ID exists
                'number_lessons' => 12,
                'amount' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'CS102',
                'course_name' => 'Advanced Programming',
                'user_id' => 3, // Ensure this user ID exists
                'number_lessons' => 15,
                'amount' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more courses as needed
        ]);
    }
}
