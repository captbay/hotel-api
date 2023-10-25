<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // data musim
        $data = [
            [
                'name' => 'High Season',
                'start_date' => '2023-12-01',
                'end_date' => '2024-01-31'
            ],
            [
                'name' => 'Low Season',
                'start_date' => '2023-10-01',
                'end_date' => '2023-11-30'
            ],
        ];

        // insert data musim
        foreach ($data as $d) {
            DB::table('musims')->insert([
                'name' => $d['name'],
                'start_date' => $d['start_date'],
                'end_date' => $d['end_date'],
            ]);
        }
    }
}
