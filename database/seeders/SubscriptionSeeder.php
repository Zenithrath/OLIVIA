<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            [
                'user_id' => 1,
                'plan_name' => 'Premium',
                'status' => 'active',
                'started_at' => now(),
                'expired_at' => now()->addYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}