<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                // user_id
                'user_id' => 6,
                'name' => 'Alex',
                'email' => 'Alex@example.com',
                'no_identitas' => '123456789',
                'no_phone' => '08123456789',
                'nama_insitusi' => 'PT. Alex',
                'address' => 'Jl. Alex',
            ]
        ];

        foreach ($data as $d) {
            DB::table('customers')->insert([
                'user_id' => $d['user_id'],
                'name' => $d['name'],
                'email' => $d['email'],
                'no_identitas' => $d['no_identitas'],
                'no_phone' => $d['no_phone'],
                'nama_insitusi' => $d['nama_insitusi'],
                'address' => $d['address'],
            ]);
        }
    }
}
