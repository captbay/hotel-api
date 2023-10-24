<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'username' => 'Admin',
                'role' => 'Admin',
            ],
            [
                'username' => 'SalesMarketing',
                'role' => 'SM',
            ],
            [
                'username' => 'GeneralManager',
                'role' => 'Admin',
            ],
            [
                'username' => 'Owner',
                'role' => 'Owner',
            ],
            [
                'username' => 'FrontOffice',
                'role' => 'FO',
            ],
            [
                'username' => 'Customer',
                'role' => 'Customer',
            ],
        ];

        foreach ($data as $d) {
            DB::table('users')->insert([
                'username' => $d['username'],
                'password' => Hash::make(12345678),
                'role' => $d['role'],
            ]);
        }
    }
}
