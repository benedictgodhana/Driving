<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the seeders in the required order
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            AssignRolesToExistingUsersSeeder::class,
        ]);
    }
}
