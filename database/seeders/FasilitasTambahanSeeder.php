<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasTambahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Extra Bed',
                'harga' => '100000'
            ],
            [
                'name' => 'Laundry',
                'harga' => '50000'
            ],
            [
                'name' => 'Massage (durasi 1 jam)',
                'harga' => '50000'
            ],
            [
                'name' => 'Meeting Room',
                'harga' => '50000'
            ],
            [
                'name' => 'Tambahan breakfast',
                'harga' => '50000'
            ]
        ];

        foreach ($data as $d) {
            DB::table('fasilitas_tambahans')->insert([
                'name' => $d['name'],
                'harga' => $d['harga'],
            ]);
        }
    }
}
