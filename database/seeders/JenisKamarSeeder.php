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
                'total_bed' => 1,
                'luas_kamar' => '22 meter persegi',
                'harga_default' => 400000,
            ],
            [
                'name' => 'SUPERIOR',
                'bed' => 'twin',
                'total_bed' => 1,
                'luas_kamar' => '22 meter persegi',
                'harga_default' => 400000,
            ],
            [
                'name' => 'DOUBLE DELUXE',
                'bed' => 'double',
                'total_bed' => 1,
                'luas_kamar' => '24 meter persegi',
                'harga_default' => 450000,
            ],
            [
                'name' => 'DOUBLE DELUXE',
                'bed' => 'twin',
                'total_bed' => 2,
                'luas_kamar' => '24 meter persegi',
                'harga_default' => 450000,
            ],
            [
                'name' => 'EXECUTIVE DELUXE',
                'bed' => 'king',
                'total_bed' => 1,
                'luas_kamar' => '36 meter persegi, menampilkan pemandangan kota',
                'harga_default' => 500000,
            ],
            [
                'name' => 'JUNIOR SUITE',
                'bed' => 'king',
                'total_bed' => 1,
                'luas_kamar' => '46 meter persegi, menampilkan pemandangan kota',
                'harga_default' => 550000,
            ]
        ];

        foreach ($data as $d) {
            DB::table('jenis_kamars')->insert([
                'name' => $d['name'],
                'bed' => $d['bed'],
                'total_bed' => $d['total_bed'],
                'luas_kamar' => $d['luas_kamar'],
                'harga_default' => $d['harga_default'],
            ]);
        }
    }
}
