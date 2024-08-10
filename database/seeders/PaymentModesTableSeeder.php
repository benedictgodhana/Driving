<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentModesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userId = 8;

        $paymentModes = [
            ['mode_name' => 'Cash', 'user_id' => $userId],
            ['mode_name' => 'Mpesa', 'user_id' => $userId],
        ];

        DB::table('payment_modes')->insert($paymentModes);
    }
}
