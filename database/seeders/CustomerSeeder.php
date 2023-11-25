<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                'nama_insitusi' => null,
                'address' => 'Jl. Alex',
            ],
            [
                // user_id
                'user_id' => 7,
                'name' => 'Dude',
                'email' => 'Dude@example.com',
                'no_identitas' => '123456789',
                'no_phone' => '08123456789',
                'nama_insitusi' => 'PT. Dude',
                'address' => 'Jl. Dude',
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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
