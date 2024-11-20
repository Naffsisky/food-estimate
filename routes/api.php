<?php

use App\Http\Controllers\Api\MahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::prefix('mahasiswa')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MahasiswaController::class, 'getAllMahasiswa']);
    Route::post('/', [MahasiswaController::class, 'createMahasiswa']);
    Route::get('/{id}', [MahasiswaController::class, 'getMahasiswa']);
    Route::put('/{id}', [MahasiswaController::class, 'updateMahasiswa']);
    Route::delete('/{id}', [MahasiswaController::class, 'deleteMahasiswa']);
});
