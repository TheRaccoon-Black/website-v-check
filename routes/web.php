<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Menampilkan daftar petugas
Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');

// Menampilkan form tambah petugas
Route::get('/petugas/create', [PetugasController::class, 'create'])->name('petugas.create');

// Menyimpan data petugas baru
Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');

// Menampilkan form edit petugas
Route::get('/petugas/{id}/edit', [PetugasController::class, 'edit'])->name('petugas.edit');

// Memperbarui data petugas
Route::put('/petugas/{id}', [PetugasController::class, 'update'])->name('petugas.update');

// Menghapus data petugas
Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
