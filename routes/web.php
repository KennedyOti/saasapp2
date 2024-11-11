<?php

use App\Http\Controllers\ClientMasterController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\InvoicesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Form Routes
Route::get('/form', [EmployeesController::class, 'view'])->name('form.view');
Route::post('/form', [EmployeesController::class, 'view'])->name('form.post');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/employees', [EmployeesController::class, 'view'])->name('employees.view');
    Route::post('/employees', [EmployeesController::class, 'view'])->name('employees.post');

    
    Route::get('/clientMaster', [ClientMasterController::class, 'view'])->name('clientMaster.view');
    Route::post('/clientMaster', [ClientMasterController::class, 'view'])->name('clientMaster.post');

    Route::get('/clients', [ClientsController::class, 'view'])->name('clients.view');
    Route::post('/clients', [ClientsController::class, 'view'])->name('clients.post');

    // Import route for handling import requests
    Route::post('/app/import', [ClientsController::class, 'import'])->name('app.import');

    // Export routes for handling export requests
    Route::get('/app/export/csv', [ClientsController::class, 'exportCsv'])->name('clients.export.csv');
    Route::get('/app/export/pdf', [ClientsController::class, 'exportPdf'])->name('clients.export.pdf');
    Route::get('/app/export/excel', [ClientsController::class, 'exportExcel'])->name('clients.export.excel'); // Added Excel export route
});

require __DIR__ . '/auth.php';
