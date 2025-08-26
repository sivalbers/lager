<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DebitorListen;
use App\Livewire\EtikettenErstellen;
use App\Livewire\ListBestand;
use App\Livewire\ScanArtikel;

use App\Http\Controllers\FileUploadController;


Route::view('/', 'welcome');

Route::view('bestand', 'bestand')
    ->middleware(['auth', 'verified'])
    ->name('bestand');

Route::get('bestand', ListBestand::class)
    ->middleware(['auth'])
    ->name('bestand');


Route::get('scanartikel', ScanArtikel::class)
    ->middleware(['auth'])
    ->name('scanartikel');


Route::get('etikettenerstellen', EtikettenErstellen::class)
    ->middleware(['auth'])
    ->name('etikettenerstellen');

Route::get('debitoren', DebitorListen::class)
    ->middleware(['auth'])
    ->name('debitoren');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::post('/upload', [FileUploadController::class, 'store']);
Route::delete('/upload/revert', [FileUploadController::class, 'revert']);

require __DIR__.'/auth.php';
