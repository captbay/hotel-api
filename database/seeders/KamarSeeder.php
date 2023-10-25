<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // data
        $data = [
            [
                'jenis_kamar_id' => 1,
                'no_kamar' => 'K1',
                'status' => 'available',
            ],
            [
                'jenis_kamar_id' => 2,
                'no_kamar' => 'K2',
                'status' => 'available',
            ],
            [
                'jenis_kamar_id' => 3,
                'no_kamar' => 'K3',
                'status' => 'available',
            ],
            [
                'jenis_kamar_id' => 4,
                'no_kamar' => 'K4',
                'status' => 'available',
            ],
            [
                'jenis_kamar_id' => 5,
                'no_kamar' => 'K5',
                'status' => 'available',
            ],
            [
                'jenis_kamar_id' => 6,
                'no_kamar' => 'K6',
                'status' => 'available',
            ],
        ];

        // insert data
        foreach ($data as $d) {
            DB::table('kamars')->insert([
                'jenis_kamar_id' => $d['jenis_kamar_id'],
                'no_kamar' => $d['no_kamar'],
                'status' => $d['status'],
            ]);
        }
    }
}
