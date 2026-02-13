<?php

use App\Helpers\QrCodeHelper;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/login');

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return Inertia::render('backend/Welcome');
    })->name('home');

    Route::get('dashboard', function () {
        return Inertia::render('backend/Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    Route::resource('sections', SectionController::class)->middleware(['auth', 'verified']);
    Route::resource('terms', TermController::class)->middleware(['auth', 'verified']);
    Route::get('/test-qr', function () {
    $svg = QrCodeHelper::generateSvg('https://www.fucina.io', 256);
    return response($svg)
        ->header('Content-Type', 'image/svg+xml');
});
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
