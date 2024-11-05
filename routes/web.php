<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AppMasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// App Routes
Route::get('/app', [AppController::class, 'view'])->name('app.view');
Route::post('/app', [AppController::class, 'view'])->name('app.post');

// App Master Routes
Route::get('/appmaster', [AppMasterController::class, 'view'])->name('appmaster.view');
Route::post('/appmaster', [AppMasterController::class, 'view'])->name('appmaster.post');

// Form Routes
Route::get('/form', [FormController::class, 'view'])->name('form.view');
Route::post('/form', [FormController::class, 'view'])->name('form.post');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Import route for handling import requests
    Route::post('/app/import', [AppController::class, 'import'])->name('app.import');

    // Export routes for handling export requests
    Route::get('/app/export/csv', [AppController::class, 'exportCsv'])->name('app.export.csv');
    Route::get('/app/export/pdf', [AppController::class, 'exportPdf'])->name('app.export.pdf');
    Route::get('/app/export/excel', [AppController::class, 'exportExcel'])->name('app.export.excel'); // Added Excel export route
});

require __DIR__ . '/auth.php';
