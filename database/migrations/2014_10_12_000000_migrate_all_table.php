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

        // Schema::create('jenis_kamars', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('bed');
        //     $table->integer('total_bed');
        //     $table->integer('harga_default');
        //     $table->timestamps();
        // });

        // Schema::create('kamars', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('jenis_kamar_id')->constrained('jenis_kamar')->onDelete('cascade');
        //     $table->string('no_kamar');
        //     $table->timestamps();
        // });
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
    }
};
