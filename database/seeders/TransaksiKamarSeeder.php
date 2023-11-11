<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiKamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // data
        $data = [
            [
                'reservasi_id' => 1,
                'kamar_id' => 1,
                'total_harga' => 550000,
            ],
            [
                'reservasi_id' => 2,
                'kamar_id' => 2,
                'total_harga' => 400000,
            ],
            [
                'reservasi_id' => 2,
                'kamar_id' => 3,
                'total_harga' => 450000,
            ]
        ];

        // insert data
        foreach ($data as $d) {
            DB::table('transaksi_kamars')->insert([
                'reservasi_id' => $d['reservasi_id'],
                'kamar_id' => $d['kamar_id'],
                'total_harga' => $d['total_harga'],
            ]);
        }
    }
}
