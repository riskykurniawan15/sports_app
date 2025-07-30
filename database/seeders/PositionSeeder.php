<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            // Goalkeeper
            ['code' => 'GK',  'name' => 'Goalkeeper', 'desc' => 'Penjaga gawang'],

            // Defenders
            ['code' => 'CB',  'name' => 'Centre Back', 'desc' => 'Bek tengah'],
            ['code' => 'LCB', 'name' => 'Left Centre Back', 'desc' => 'Bek tengah kiri'],
            ['code' => 'RCB', 'name' => 'Right Centre Back', 'desc' => 'Bek tengah kanan'],
            ['code' => 'LB',  'name' => 'Left Back', 'desc' => 'Bek sayap kiri'],
            ['code' => 'RB',  'name' => 'Right Back', 'desc' => 'Bek sayap kanan'],
            ['code' => 'LWB', 'name' => 'Left Wing Back', 'desc' => 'Bek sayap kiri menyerang'],
            ['code' => 'RWB', 'name' => 'Right Wing Back', 'desc' => 'Bek sayap kanan menyerang'],
            ['code' => 'CWB', 'name' => 'Complete Wing Back', 'desc' => 'Bek sayap serba bisa ofensif'],
            ['code' => 'IFB', 'name' => 'Inverted Full Back', 'desc' => 'Bek sayap masuk ke tengah'],
            ['code' => 'SW',  'name' => 'Sweeper', 'desc' => 'Libero (bek sapu)'],

            // Midfielders
            ['code' => 'CM',  'name' => 'Centre Midfielder', 'desc' => 'Gelandang tengah'],
            ['code' => 'CDM', 'name' => 'Central Defensive Midfielder', 'desc' => 'Gelandang bertahan'],
            ['code' => 'DM',  'name' => 'Defensive Midfielder', 'desc' => 'Gelandang bertahan'],
            ['code' => 'B2B', 'name' => 'Box-to-Box Midfielder', 'desc' => 'Gelandang serba bisa'],
            ['code' => 'CAM', 'name' => 'Central Attacking Midfielder', 'desc' => 'Gelandang serang'],
            ['code' => 'AM',  'name' => 'Attacking Midfielder', 'desc' => 'Gelandang serang'],
            ['code' => 'LM',  'name' => 'Left Midfielder', 'desc' => 'Gelandang kiri'],
            ['code' => 'RM',  'name' => 'Right Midfielder', 'desc' => 'Gelandang kanan'],
            ['code' => 'LWM', 'name' => 'Left Wide Midfielder', 'desc' => 'Gelandang sayap kiri melebar'],
            ['code' => 'RWM', 'name' => 'Right Wide Midfielder', 'desc' => 'Gelandang sayap kanan melebar'],

            // Playmaker & Role Khusus
            ['code' => 'DLP', 'name' => 'Deep Lying Playmaker', 'desc' => 'Pengatur serangan dari belakang'],
            ['code' => 'RPM', 'name' => 'Roaming Playmaker', 'desc' => 'Playmaker bebas bergerak'],
            ['code' => 'AP',  'name' => 'Advanced Playmaker', 'desc' => 'Playmaker menyerang'],
            ['code' => 'TQ',  'name' => 'Trequartista', 'desc' => 'Playmaker bebas di depan'],
            ['code' => 'HB',  'name' => 'Half Back', 'desc' => 'Gelandang bertahan turun ke bek'],

            // Forwards
            ['code' => 'CF',  'name' => 'Centre Forward', 'desc' => 'Penyerang tengah'],
            ['code' => 'ST',  'name' => 'Striker', 'desc' => 'Penyerang murni'],
            ['code' => 'SS',  'name' => 'Second Striker', 'desc' => 'Penyerang bayangan'],
            ['code' => 'LW',  'name' => 'Left Winger', 'desc' => 'Penyerang sayap kiri'],
            ['code' => 'RW',  'name' => 'Right Winger', 'desc' => 'Penyerang sayap kanan'],
            ['code' => 'IW',  'name' => 'Inverted Winger', 'desc' => 'Winger yang sering masuk ke tengah'],
            ['code' => 'IF',  'name' => 'Inside Forward', 'desc' => 'Penyerang sayap menusuk'],
            ['code' => 'WF',  'name' => 'Wide Forward', 'desc' => 'Penyerang sayap lebar'],

            // Special Striker Roles
            ['code' => 'F9',  'name' => 'False 9', 'desc' => 'Penyerang palsu yang turun ke tengah'],
            ['code' => 'TM',  'name' => 'Target Man', 'desc' => 'Penyerang pemantul bola'],
            ['code' => 'PO',  'name' => 'Poacher', 'desc' => 'Striker pemburu gol di kotak penalti'],
            ['code' => 'PF',  'name' => 'Pressing Forward', 'desc' => 'Striker yang fokus menekan lawan'],
        ]);
    }
}
