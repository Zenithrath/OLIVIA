<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegalArticleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('legal_articles')->insert([
            [
                'title' => 'Dasar Hukum Kontrak',
                'slug' => 'dasar-hukum-kontrak',
                'content' => 'Kontrak adalah perjanjian yang mengikat para pihak.',
                'author' => 'System',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}