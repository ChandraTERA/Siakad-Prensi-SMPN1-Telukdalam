<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GpsValidationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes with session middleware
Route::middleware(['web'])->prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/check', [AuthController::class, 'checkAuth'])->name('api.auth.check');
});

// GPS Validation API routes
Route::prefix('gps')->group(function () {
    Route::post('/validate', [GpsValidationController::class, 'validateLocation'])->name('api.gps.validate');
    Route::get('/school-location', [GpsValidationController::class, 'getSchoolLocation'])->name('api.gps.school-location');
    Route::post('/test', [GpsValidationController::class, 'testLocation'])->name('api.gps.test');
});

// Protected API routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::post('/absen-mandiri/validate-gps', [App\Http\Controllers\Siswa\AbsenMandiriController::class, 'validateGps'])->name('api.siswa.absen-mandiri.validate-gps');
});
