<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MuseumController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/backend');
Route::redirect('/museum', '/museum/1/it');

Route::prefix('backend')->group(function () {
    Route::get('/', function () {
        return Inertia::render('backend/Welcome');
    })->name('home');

    Route::get('dashboard', function () {
        return Inertia::render('backend/Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::resource('languages', LanguageController::class)->middleware(['auth', 'verified']);
    Route::resource('media', MediaController::class)->middleware(['auth', 'verified'])->names('media');
    Route::resource('museums', MuseumController::class)->middleware(['auth', 'verified']);
    Route::resource('exhibitions', ExhibitionController::class)->middleware(['auth', 'verified']);
    Route::resource('posts', PostController::class)->middleware(['auth', 'verified']);
});

Route::get('museum/{museumId}/{language?}', function ($museumId = null, $language = 'it') {
    return Inertia::render('frontend/Museum', [ 'museumId' => $museumId, 'language' => $language ]);
})->name('museum')->where('language', '[a-z]{2}')->where('museumId', '[0-9]+');
Route::get('museum/{museumId}/collection/{collectionId}/{language?}', function ($museumId = null, $collectionId = null, $language = 'it') {
    return Inertia::render('frontend/Collection', ['museumId' => $museumId, 'collectionId' => $collectionId, 'language' => $language]);
})->name('collection.index')->where('language', '[a-z]{2}');
Route::get('museum/{museumId}/collection/{collectionId}/post/{postId}/{language?}', function ($museumId = null, $collectionId = null, $postId = null, $language = 'it',) {
    return Inertia::render('frontend/Post', ['museumId' => $museumId, 'collectionId' => $collectionId, 'postId' => $postId, 'language' => $language]);
})->name('post');


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
