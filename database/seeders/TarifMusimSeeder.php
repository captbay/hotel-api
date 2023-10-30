<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifMusimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // data tarif musim
        $data = [
            [
                'jenis_kamar_id' => 1,
                'musim_id' => 1,
                'harga' => 1000000
            ],
            [
                'jenis_kamar_id' => 2,
                'musim_id' => 2,
                'harga' => 20000
            ]
        ];

        // insert data tarif musim
        foreach ($data as $d) {
            DB::table('tarif_musims')->insert([
                'jenis_kamar_id' => $d['jenis_kamar_id'],
                'musim_id' => $d['musim_id'],
                'harga' => $d['harga'],
            ]);
        }
    }
}
