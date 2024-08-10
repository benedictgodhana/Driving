<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentPaymentsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $studentPayments = [
            [
                'student_id' => 3,
                'payment_mode_id' => 1,
                'payment_amount' => 5000.00,
                'payment_date' => '2024-08-09',
                'reference_number' => 'REF123456789',
                'user_id' => 8,
            ],
            [
                'student_id' => 4,
                'payment_mode_id' => 2,
                'payment_amount' => 3000.00,
                'payment_date' => '2024-08-10',
                'reference_number' => 'REF987654321',
                'user_id' => 8,
            ],
        ];

        // Insert the student payment data into the student_payments table
        DB::table('student_payments')->insert($studentPayments);
    }
}
