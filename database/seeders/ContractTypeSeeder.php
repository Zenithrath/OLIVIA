<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contract_types')->insert([
            [
                'name' => 'NDA',
                'description' => 'Non Disclosure Agreement',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employment Contract',
                'description' => 'Kontrak kerja karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vendor Agreement',
                'description' => 'Perjanjian vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Partnership Agreement',
                'description' => 'Kerja sama bisnis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
