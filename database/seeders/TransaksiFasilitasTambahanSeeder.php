<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiFasilitasTambahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //data
        $data = [
            [
                'reservasi_id' => 1,
                'fasilitas_tambahan_id' => 1,
                'jumlah' => 1,
                'total_harga' => 100000,
            ]
        ];

        // insert data
        foreach ($data as $d) {
            DB::table('transaksi_fasilitas_tambahans')->insert([
                'reservasi_id' => $d['reservasi_id'],
                'fasilitas_tambahan_id' => $d['fasilitas_tambahan_id'],
                'jumlah' => $d['jumlah'],
                'total_harga' => $d['total_harga'],
            ]);
        }
    }
}
