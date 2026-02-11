<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\GlossaryController;
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
    Route::resource('glossaries', GlossaryController::class)->middleware(['auth', 'verified']);
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
