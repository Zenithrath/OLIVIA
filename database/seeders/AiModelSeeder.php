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
                'model_name' => 'llama3',
                'provider' => 'Ollama',
                'version' => 'latest',
                'is_local' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_name' => 'qwen3:8b',
                'provider' => 'Ollama',
                'version' => '8B',
                'is_local' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'model_name' => 'nomic-embed-text',
                'provider' => 'Ollama',
                'version' => 'latest',
                'is_local' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}