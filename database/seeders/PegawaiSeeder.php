<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                // user_id
                'user_id' => 1,
                'name' => 'Admin',
                'no_phone' => '08123456789',
                'address' => 'Jl. Admin',
            ],
            // sm
            [
                // user_id
                'user_id' => 2,
                'name' => 'Sales Marketing',
                'no_phone' => '08123456789',
                'address' => 'Jl. Marketing',
            ],
            // gm
            [
                // user_id
                'user_id' => 3,
                'name' => 'General Manager',
                'no_phone' => '08123456789',
                'address' => 'Jl. Manager',
            ],
            // owner
            [
                // user_id
                'user_id' => 4,
                'name' => 'Owner',
                'no_phone' => '08123456789',
                'address' => 'Jl. Owner',
            ],
            // fo
            [
                // user_id
                'user_id' => 5,
                'name' => 'Front Office',
                'no_phone' => '08123456789',
                'address' => 'Jl. Office',
            ],
        ];

        foreach ($data as $d) {
            DB::table('pegawais')->insert([
                'user_id' => $d['user_id'],
                'name' => $d['name'],
                'no_phone' => $d['no_phone'],
                'address' => $d['address'],
            ]);
        }
    }
}
