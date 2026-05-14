<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AiModelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ai_models')->insert([
            [
                'model_name' => 'GPT-4',
                'provider' => 'OpenAI',
                'version' => '4.0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_name' => 'Gemini',
                'provider' => 'Google',
                'version' => '1.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_name' => 'Claude',
                'provider' => 'Anthropic',
                'version' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}