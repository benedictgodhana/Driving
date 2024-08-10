<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert predefined application types with descriptions
        DB::table('application_types')->insert([
            [
                'name' => 'Service-Based Learning',
                'description' => 'Programs and activities that provide practical experience and service opportunities, typically in a community or service-oriented context.'
            ],
            [
                'name' => 'Work-Based Learning',
                'description' => 'Opportunities for learning and gaining skills directly in a work environment, often including internships or apprenticeships.'
            ],
        ]);
    }
}
