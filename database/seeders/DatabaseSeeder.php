<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // run user seeds
        $this->call(UserSeeder::class);
        // run customer seeds
        $this->call(CustomerSeeder::class);
        // run pegawai seeds
        $this->call(PegawaiSeeder::class);
    }
}
