<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = DB::table('positions')->pluck('id', 'code');

        DB::table('players')->insert([
            // ===== PERSIJA JAKARTA STARTING XI =====
            ['name' => 'Andritany Ardhiyasa', 'team_id' => 1, 'squad_number' => 1, 'height' => 178, 'weight' => 75, 'position_id' => $positions['GK'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rio Fahmi',           'team_id' => 1, 'squad_number' => 2, 'height' => 170, 'weight' => 60, 'position_id' => $positions['RB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hansamu Yama',        'team_id' => 1, 'squad_number' => 23, 'height' => 180, 'weight' => 75, 'position_id' => $positions['CB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ryuji Utomo',         'team_id' => 1, 'squad_number' => 4, 'height' => 183, 'weight' => 78, 'position_id' => $positions['CB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Michael Krmencik',    'team_id' => 1, 'squad_number' => 6, 'height' => 175, 'weight' => 68, 'position_id' => $positions['LB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Resky Fandi',         'team_id' => 1, 'squad_number' => 28, 'height' => 172, 'weight' => 65, 'position_id' => $positions['CDM'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Abimanyu',            'team_id' => 1, 'squad_number' => 18, 'height' => 174, 'weight' => 64, 'position_id' => $positions['CM'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hanif Sjahbandi',     'team_id' => 1, 'squad_number' => 19, 'height' => 180, 'weight' => 70, 'position_id' => $positions['CM'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Riko Simanjuntak',    'team_id' => 1, 'squad_number' => 25, 'height' => 158, 'weight' => 55, 'position_id' => $positions['RW'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Witan Sulaeman',      'team_id' => 1, 'squad_number' => 8, 'height' => 170, 'weight' => 62, 'position_id' => $positions['LW'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marko Simic',         'team_id' => 1, 'squad_number' => 9, 'height' => 187, 'weight' => 85, 'position_id' => $positions['ST'], 'created_at' => now(), 'updated_at' => now()],

            // ===== PERSIB BANDUNG STARTING XI =====
            ['name' => 'Teja Paku Alam',      'team_id' => 2, 'squad_number' => 14, 'height' => 182, 'weight' => 78, 'position_id' => $positions['GK'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Henhen Herdiana',     'team_id' => 2, 'squad_number' => 12, 'height' => 172, 'weight' => 62, 'position_id' => $positions['RB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nick Kuipers',        'team_id' => 2, 'squad_number' => 2, 'height' => 193, 'weight' => 85, 'position_id' => $positions['CB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Achmad Jufriyanto',   'team_id' => 2, 'squad_number' => 16, 'height' => 182, 'weight' => 78, 'position_id' => $positions['CB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dedi Kusnandar',      'team_id' => 2, 'squad_number' => 11, 'height' => 172, 'weight' => 65, 'position_id' => $positions['LB'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marc Klok',           'team_id' => 2, 'squad_number' => 10, 'height' => 177, 'weight' => 70, 'position_id' => $positions['CDM'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dedi Kusnandar',      'team_id' => 2, 'squad_number' => 11, 'height' => 172, 'weight' => 65, 'position_id' => $positions['CM'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ezra Walian',         'team_id' => 2, 'squad_number' => 30, 'height' => 186, 'weight' => 80, 'position_id' => $positions['CAM'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Febri Hariyadi',      'team_id' => 2, 'squad_number' => 13, 'height' => 170, 'weight' => 60, 'position_id' => $positions['LW'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ciro Alves',          'team_id' => 2, 'squad_number' => 77, 'height' => 180, 'weight' => 78, 'position_id' => $positions['RW'], 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'David da Silva',      'team_id' => 2, 'squad_number' => 19, 'height' => 185, 'weight' => 82, 'position_id' => $positions['ST'], 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
