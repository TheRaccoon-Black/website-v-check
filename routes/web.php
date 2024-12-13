<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PemeriksaanController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login-logs', [LoginLogController::class, 'index'])->name('login-logs.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//admin routes
Route::middleware(['auth','role:admin'])->group(function () {
    // Route::get('/admin/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/', [PetugasController::class, 'index'])->name('index');
        Route::get('/create', [PetugasController::class, 'create'])->name('create');
        Route::post('/', [PetugasController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PetugasController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PetugasController::class, 'update'])->name('update');
        Route::delete('/{id}', [PetugasController::class, 'destroy'])->name('destroy');
    });
});

//admin routes
Route::middleware(['auth','role:petugas'])->group(function () {
    Route::prefix("pemeriksaan")->name("pemeriksaan.")->group(function () {
        Route::get("/", [PemeriksaanController::class, "index"])->name("index");
        Route::get('/create', [PemeriksaanController::class, 'create'])->name('create');
        Route::get('/cetak/{id_hasil}', [PemeriksaanController::class, 'cetak'])->name('cetak');
        Route::post('/store', [PemeriksaanController::class, 'store'])->name('store');
        Route::get('/rekap', [PemeriksaanController::class, 'recap'])->name('recap');
        Route::get('/arsip/{id_hasil}', [PemeriksaanController::class, 'arsip'])->name('arsip');
        Route::get('/fetch', [PemeriksaanController::class, 'fetch'])->name('fetch');
    });

});


// --------------------------------------------------------------------------
// Checklist Routes
// --------------------------------------------------------------------------
Route::prefix('checklist')->name('checklist.')->group(function () {
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
Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
    Route::get('/', [KendaraanController::class, 'index'])->name('index');
    Route::get('/create', [KendaraanController::class, 'create'])->name('create');
    Route::post('/', [KendaraanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [KendaraanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KendaraanController::class, 'update'])->name('update');
    Route::delete('/{id}', [KendaraanController::class, 'destroy'])->name('destroy');
});


Route::get('/view-pdf', [PemeriksaanController::class, 'showpdf'])->name('view.showpdf');
require __DIR__ . '/auth.php';
