<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('bestand', 'bestand')
    ->middleware(['auth', 'verified'])
    ->name('bestand');


Route::view('scanartikel', 'scanartikel')
    ->middleware(['auth', 'verified'])
    ->name('scanartikel');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
