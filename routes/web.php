<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\KendaraanController;

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

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// --------------------------------------------------------------------------
// Petugas Routes
// --------------------------------------------------------------------------
Route::prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/', [PetugasController::class, 'index'])->name('index');
    Route::get('/create', [PetugasController::class, 'create'])->name('create');
    Route::post('/', [PetugasController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PetugasController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PetugasController::class, 'update'])->name('update');
    Route::delete('/{id}', [PetugasController::class, 'destroy'])->name('destroy');
});

// --------------------------------------------------------------------------
// Checklist Routes
// --------------------------------------------------------------------------
Route::prefix('checklists')->name('checklists.')->group(function () {
    Route::get('/', [ChecklistController::class, 'index'])->name('index');
    Route::get('/create', [ChecklistController::class, 'create'])->name('create');
    Route::post('/', [ChecklistController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ChecklistController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ChecklistController::class, 'update'])->name('update');
    Route::delete('/{id}', [ChecklistController::class, 'destroy'])->name('destroy');
});

// --------------------------------------------------------------------------
// Kendaraan Routes
// --------------------------------------------------------------------------
Route::prefix('kendaraans')->name('kendaraans.')->group(function () {
    Route::get('/', [KendaraanController::class, 'index'])->name('index');
    Route::get('/create', [KendaraanController::class, 'create'])->name('create');
    Route::post('/', [KendaraanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KendaraanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KendaraanController::class, 'update'])->name('update');
    Route::delete('/{id}', [KendaraanController::class, 'destroy'])->name('destroy');
});
