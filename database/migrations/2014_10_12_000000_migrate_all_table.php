<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // relation table user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('no_identitas');
            $table->string('no_phone');
            $table->string('nama_insitusi')->nullable();
            $table->string('address');
            $table->timestamps();
        });

        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            // relation table user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('no_phone');
            $table->string('address');
            $table->timestamps();
        });

        Schema::create('jenis_kamars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bed');
            $table->integer('total_bed');
            $table->string('luas_kamar');
            $table->integer('harga_default');
            $table->timestamps();
        });

        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_kamar_id')->constrained('jenis_kamars')->onDelete('cascade');
            $table->string('no_kamar');
            $table->string('status'); // available, booked, unavailable
            $table->timestamps();
        });

        // fasilitas_tambahan
        Schema::create('fasilitas_tambahans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('harga');
            $table->timestamps();
        });

        // musims
        Schema::create('musims', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        // tarif_musim
        Schema::create('tarif_musims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_kamar_id')->constrained('jenis_kamars')->onDelete('cascade');
            $table->foreignId('musim_id')->constrained('musims')->onDelete('cascade');
            $table->integer('harga');
            $table->timestamps();
        });

        // 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('customer');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('jenis_kamar');
        Schema::dropIfExists('kamar');
        Schema::dropIfExists('fasilitas_tambahan');
        Schema::dropIfExists('musim');
        Schema::dropIfExists('tarif_musim');
    }
};
