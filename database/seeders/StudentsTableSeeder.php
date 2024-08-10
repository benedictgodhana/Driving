<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            [
                'name' => 'John Mkare',
                'email' => 'john@example.com',
                'student_number' => 'S123456',
                'ID_Number' => 'ID12345678',
                'address' => 'Mombasa',
                'date_of_birth' => '2000-01-15',
                'course_id' => 7,
                'user_id' => 8,
            ],
            [
                'name' => 'Hussein Adan',
                'email' => 'hussen@example.com',
                'student_number' => 'S123457',
                'ID_Number' => 'ID12345679',
                'address' => ' Nairobi',
                'date_of_birth' => '1999-05-22',
                'course_id' => 8,
                'user_id' => 8,
            ],
        ];

        DB::table('students')->insert($students);
    }
}
