<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedFlagSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('red_flags')->insert([
            [
                'code' => 'RF001',
                'title' => 'Unlimited Liability',
                'description' => 'Liabilitas tanpa batas',
                'severity' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'RF002',
                'title' => 'Automatic Renewal',
                'description' => 'Perpanjangan otomatis',
                'severity' => 'medium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'RF003',
                'title' => 'Unclear Payment Terms',
                'description' => 'Ketentuan pembayaran tidak jelas',
                'severity' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}