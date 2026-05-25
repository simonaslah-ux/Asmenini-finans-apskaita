<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    Route::get('/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');
    Route::post('/reports/send-email', [ReportController::class, 'sendEmail'])->name('reports.sendEmail');

});

require __DIR__.'/auth.php';
