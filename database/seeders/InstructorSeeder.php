<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get a random user to assign as the creator of the instructor

        DB::table('instructors')->insert([
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'tel' => '0712345678',
                'id_number' => '12345678',
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'tel' => '0798765432',
                'id_number' => '87654321',
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alicejohnson@example.com',
                'tel' => '0700123456',
                'id_number' => '23456789',
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
