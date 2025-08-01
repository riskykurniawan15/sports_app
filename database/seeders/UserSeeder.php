<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "Risky Kurniawan",
            'email' => 'riskykurniawan15@gmail.com',
            'password' => Hash::make('kurniawan'),
        ]);
    }
}
