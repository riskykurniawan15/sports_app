<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            [
                'name' => 'Persija Jakarta',
                'logo' => 'https://upload.wikimedia.org/wikipedia/id/4/4a/Persija_Jakarta_logo.svg',
                'established_year' => 1928,
                'address' => 'Jl. RM Harsono No.2, Ragunan, Pasar Minggu, Jakarta Selatan',
                'city' => '31.71',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Persib Bandung',
                'logo' => 'https://upload.wikimedia.org/wikipedia/id/6/6a/Persib_logo.svg',
                'established_year' => 1933,
                'address' => 'Jl. Sulanjana No.17, Dago, Coblong, Bandung',
                'city' => '32.73',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
