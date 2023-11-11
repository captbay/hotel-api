<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'customer_id' => 1,
                'pegawai_id' => null,
                'kode_booking' => 'P301223-001',
                'tanggal_reservasi' => '2023-10-28',
                'tanggal_end_reservasi' => '2023-10-29',
                'check_in' => '2023-10-28 14:00:00',
                'check_out' => '2022-12-31 12:00:00',
                'status' => 'selesai',
                'dewasa' => 2,
                'anak' => 0,
                'total_jaminan' => 550000, //minimal 50% dari total harga (grup) && (personal 100%)
                'total_deposit' => 300000,
                'total_harga' => 950000,
                'tanggal_pembayaran_lunas' => '2023-10-29',
            ],
            [
                'customer_id' => 2,
                'pegawai_id' => 2,
                'kode_booking' => 'G301223-002',
                'tanggal_reservasi' => '2023-10-28',
                'tanggal_end_reservasi' => '2023-10-29',
                'check_in' => '2023-10-28 14:00:00',
                'check_out' => '2023-10-29 12:00:00',
                'status' => 'selesai',
                'dewasa' => 2,
                'anak' => 2,
                'total_jaminan' => 425000, //minimal 50% dari total harga (grup) && (personal 100%)
                'total_deposit' => 600000,
                'total_harga' => 1450000,
                'tanggal_pembayaran_lunas' => '2023-10-29',
            ]
        ];

        foreach ($data as $d) {
            DB::table('reservasis')->insert([
                'customer_id' => $d['customer_id'],
                'pegawai_id' => $d['pegawai_id'],
                'kode_booking' => $d['kode_booking'],
                'tanggal_reservasi' => $d['tanggal_reservasi'],
                'tanggal_end_reservasi' => $d['tanggal_end_reservasi'],
                'check_in' => $d['check_in'],
                'check_out' => $d['check_out'],
                'status' => $d['status'],
                'dewasa' => $d['dewasa'],
                'anak' => $d['anak'],
                'total_jaminan' => $d['total_jaminan'],
                'total_deposit' => $d['total_deposit'],
                'total_harga' => $d['total_harga'],
                'tanggal_pembayaran_lunas' => $d['tanggal_pembayaran_lunas'],
            ]);
        }
    }
}
