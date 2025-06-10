<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RunningHoursController;
use App\Http\Controllers\ReportController;


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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/running-hours', [DashboardController::class, 'runningHours'])->name('running.hours');

    Route::get('/running-hours', [RunningHoursController::class, 'index'])->name('running_hours.index');
    Route::post('/running-hours', [RunningHoursController::class, 'store'])->name('running_hours.store');


    Route::get('/spareparts/{type}', [SparepartController::class, 'index'])->name('spareparts.index');
    Route::middleware(['auth', 'admin'])->group(function () { // 'auth' memastikan user login dulu, 'admin' cek rolenya
        // Rute untuk Form Tambah Sparepart
        Route::get('/spareparts/{type}/create', [SparepartController::class, 'create'])->name('spareparts.create');
        // Rute untuk Menyimpan Data Sparepart Baru
        Route::post('/spareparts/{type}', [SparepartController::class, 'store'])->name('spareparts.store');
        Route::put('/request-list/{id}/{status}', [SparepartController::class, 'updateStatus'])->name('request.sparepart.updateStatus');
        // Rute untuk menampilkan form penggantian sparepart running hour
    Route::get('/running-hours/replace/{id}/form', [RunningHoursController::class, 'showReplaceForm'])->name('running_hours.show_replace_form');
        Route::put('/running-hours/replace/{id}', [RunningHoursController::class, 'replaceSparepart'])->name('running_hours.replace_sparepart');
    });
    // Route::get('/spareparts/{type}/create', [SparepartController::class, 'create'])->name('spareparts.create');
    // Route::post('/spareparts/{type}', [SparepartController::class, 'store'])->name('spareparts.store');
    Route::get('/request-sparepart/{type}', [SparepartController::class, 'createRequest'])->name('request.sparepart.create');
    Route::post('/request-sparepart/{type}', [SparepartController::class, 'submitRequest'])->name('request.sparepart.submit');
    Route::get('/request-list', [SparepartController::class, 'requestList'])->name('request.list');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/download', [ReportController::class, 'download'])->name('reports.download'); // Route untuk download
});

require __DIR__.'/auth.php';
