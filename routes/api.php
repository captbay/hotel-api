<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FasilitasTambahanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JenisKamarController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\MusimController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\TarifMusimController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['prefix' => 'kamar'], function () {
    // get all kamar information
    Route::get('information', [KamarController::class, 'information']);
});

Route::group(['prefix' => 'auth', 'middleware' => ['auth:sanctum']], function () {
    //logout
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    //  change password
    Route::post('change-password', [AuthController::class, 'changePassword']);
});

// user management
Route::group(['prefix' => 'user-management', 'middleware' => ['auth:sanctum']], function () {
    // sm regis cust group
    Route::post('register-customer-group', [AuthController::class, 'registerCustGroup']);
});

// kamar
Route::group(['prefix' => 'kamar', 'middleware' => ['auth:sanctum']], function () {
    // dashboard
    Route::get('dashboard', [KamarController::class, 'dashboard']);
    // get all kamar
    Route::get('all', [KamarController::class, 'index']);
    // get kamar by id
    Route::get('get/{id}', [KamarController::class, 'show']);
    // create kamar
    Route::post('create', [KamarController::class, 'create']);
    // update kamar
    Route::put('update/{id}', [KamarController::class, 'update']);
    // delete kamar
    Route::delete('delete/{id}', [KamarController::class, 'destroy']);
});

// jenis kamar
Route::group(['prefix' => 'jenis-kamar', 'middleware' => ['auth:sanctum']], function () {
    // get all jenis kamar
    Route::get('all', [JenisKamarController::class, 'index']);
});

// musim
Route::group(['prefix' => 'musim', 'middleware' => ['auth:sanctum']], function () {
    // get all musim
    Route::get('all', [MusimController::class, 'index']);
    // get musim by id
    Route::get('get/{id}', [MusimController::class, 'show']);
    // create musim
    Route::post('create', [MusimController::class, 'create']);
    // update musim
    Route::put('update/{id}', [MusimController::class, 'update']);
    // delete musim
    Route::delete('delete/{id}', [MusimController::class, 'destroy']);
});

// fasilitas tambahan
Route::group(['prefix' => 'fasilitas-tambahan', 'middleware' => ['auth:sanctum']], function () {
    // get all fasilitas tambahan
    Route::get('all', [FasilitasTambahanController::class, 'index']);
    // get fasilitas tambahan by id
    Route::get('get/{id}', [FasilitasTambahanController::class, 'show']);
    // create fasilitas tambahan
    Route::post('create', [FasilitasTambahanController::class, 'create']);
    // update fasilitas tambahan
    Route::put('update/{id}', [FasilitasTambahanController::class, 'update']);
    // delete fasilitas tambahan
    Route::delete('delete/{id}', [FasilitasTambahanController::class, 'destroy']);
});

// tarif musim
Route::group(['prefix' => 'tarif-musim', 'middleware' => ['auth:sanctum']], function () {
    // get all tarif musim
    Route::get('all', [TarifMusimController::class, 'index']);
    // get tarif musim by id
    Route::get('get/{id}', [TarifMusimController::class, 'show']);
    // create tarif musim
    Route::post('create', [TarifMusimController::class, 'create']);
    // update tarif musim
    Route::put('update/{id}', [TarifMusimController::class, 'update']);
    // delete tarif musim
    Route::delete('delete/{id}', [TarifMusimController::class, 'destroy']);
});

// customer
Route::group(['prefix' => 'customer', 'middleware' => ['auth:sanctum']], function () {
    // get all customer
    Route::get('all', [CustomerController::class, 'index']);
    // get customer grup
    Route::get('grup', [CustomerController::class, 'indexGrup']);
    // get customer by id
    Route::get('get/{id}', [CustomerController::class, 'show']);
    // update customer
    Route::put('update/{id}', [CustomerController::class, 'update']);
});

// reservasi
Route::group(['prefix' => 'reservasi', 'middleware' => ['auth:sanctum']], function () {
    // get all reservasi
    Route::get('all', [ReservasiController::class, 'index']);
    // get reservasi by id
    Route::get('get/{id}', [ReservasiController::class, 'show']);

    // get all reservasi khusu cust login
    Route::get('all/customer', [ReservasiController::class, 'indexCustomer']);
    // create reservasi
    Route::post('create', [ReservasiController::class, 'create']);
    // create tanda terima reservasi
    Route::get('create/tanda-terima/{id}', [ReservasiController::class, 'createTandaTerima']);
    // show list reservasi yang bisa dibatalkan
    Route::get('cancel/list', [ReservasiController::class, 'cancelList']);
    // put pembatalan pemesanan
    Route::put('cancel/{id}', [ReservasiController::class, 'cancel']);
    // list reservasi grup
    Route::get('grup/list', [ReservasiController::class, 'listReservasiGrup']);
    // input uang jaminan grup
    Route::put('grup/jaminan/{id}', [ReservasiController::class, 'inputJaminanGrup']);
    // get all reservasi khusus FO
    Route::get('all/fo', [ReservasiController::class, 'indexFo']);
    // fungsi cek in untuk fo
    Route::put('cek-in/{id}', [ReservasiController::class, 'cekIn']);
    // fungsi cek out untuk fo
    Route::put('cek-out/{id}', [ReservasiController::class, 'cekOut']);
    // tambah fasilitas
    Route::post('tambah-fasilitas/{id}', [ReservasiController::class, 'tambahFasilitas']);
    // // update reservasi
    // Route::put('update/{id}', [ReservasiController::class, 'update']);
    // // delete reservasi
    // Route::delete('delete/{id}', [ReservasiController::class, 'destroy']);
});

// invoice
Route::group(['prefix' => 'invoice', 'middleware' => ['auth:sanctum']], function () {
    // get all invoice
    // Route::get('all', [InvoiceController::class, 'index']);
    // get invoice by id
    // Route::get('get/{id}', [InvoiceController::class, 'show']);
    // create invoice
    Route::post('create/{id}', [InvoiceController::class, 'create']);
    // update invoice
    // Route::put('update/{id}', [InvoiceController::class, 'update']);
    // delete invoice
    // Route::delete('delete/{id}', [InvoiceController::class, 'destroy']);
});
