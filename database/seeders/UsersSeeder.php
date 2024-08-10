<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Insert users
        $users = [
            [
                'full_name' => 'Student',
                'email' => 'student@example.com',
                'username' => 'student',
                'telephone' => '123-456-7890',
                'station_id' => 1, // Ensure this station ID exists
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'full_name' => 'Admin',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'telephone' => '987-654-3210',
                'station_id' => 2, // Ensure this station ID exists
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            // Add more users as needed
        ];

        foreach ($users as $userData) {
            $user = DB::table('users')->insertGetId($userData);

            // Assign roles based on the user's data or any other logic
            $userModel = \App\Models\User::find($user);

            // Example: Assigning roles
            if ($userModel->email === 'student@example.com') {
                $userModel->assignRole('student');
            } elseif ($userModel->email === 'admin@example.com') {
                $userModel->assignRole('admin');
            }
        }
    }
}
