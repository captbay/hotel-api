<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'SUPERIOR',
                'bed' => 'double',
                'total_beds' => 1,
                'harga_default' => '100000',
            ],
            [
                'name' => 'SUPERIOR',
                'bed' => 'twin',
                'total_beds' => 1,
                'harga_default' => '100001',
            ],
            [
                'name' => 'DOUBLE DELUXE',
                'bed' => 'double',
                'total_beds' => 1,
                'harga_default' => '100001',
            ],
            [
                'name' => 'DOUBLE DELUXE',
                'bed' => 'twin',
                'total_beds' => 2,
                'harga_default' => '100001',
            ],
        ];

        foreach ($data as $d) {
            DB::table('jenis_kamars')->insert([
                'name' => $d['name'],
                'bed' => $d['bed'],
                'harga_default' => $d['harga_default'],
            ]);
        }
    }
}
