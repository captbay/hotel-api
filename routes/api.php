<?php

use App\Http\Controllers\AuthController;
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

//!! check apakah kalo ga login itu masih bisa get data ga