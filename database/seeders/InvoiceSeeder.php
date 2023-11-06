<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
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
                'pegawai_id' => 5,
                'no_invoice' => 'P301223-001',
                'tanggal_lunas_nota' => '2022-12-31',
                'total_harga' => 650000,
                'total_pajak' => 6500,
                'total_pembayaran' => 656500
            ],
            [
                'reservasi_id' => 2,
                'pegawai_id' => 5,
                'no_invoice' => 'G301223-002',
                'tanggal_lunas_nota' => '2022-12-31',
                'total_harga' => 1450000,
                'total_pajak' => 14500,
                'total_pembayaran' => 1464500
            ],
        ];

        // insert data
        foreach ($data as $d) {
            DB::table('invoices')->insert([
                'reservasi_id' => $d['reservasi_id'],
                'pegawai_id' => $d['pegawai_id'],
                'no_invoice' => $d['no_invoice'],
                'tanggal_lunas_nota' => $d['tanggal_lunas_nota'],
                'total_harga' => $d['total_harga'],
                'total_pajak' => $d['total_pajak'],
                'total_pembayaran' => $d['total_pembayaran']
            ]);
        }
    }
}
