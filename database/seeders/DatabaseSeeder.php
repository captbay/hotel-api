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
        // run jenis kamar seeds
        $this->call(JenisKamarSeeder::class);
        // run kamar seeds
        $this->call(KamarSeeder::class);
        // run fasilitas tambahan seeds
        $this->call(FasilitasTambahanSeeder::class);
        // run musim seeds
        $this->call(MusimSeeder::class);
        // run tarif musim seeds
        $this->call(TarifMusimSeeder::class);
        // run reservasi seeds
        $this->call(ReservasiSeeder::class);
        // run transaksi kamar seeds
        $this->call(TransaksiKamarSeeder::class);
        // run transaksi fasilitas tambahan seeds
        $this->call(TransaksiFasilitasTambahanSeeder::class);
    }
}
