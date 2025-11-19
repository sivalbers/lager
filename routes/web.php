<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DebitorListen;
use App\Livewire\EtikettenErstellen;
use App\Livewire\ListBestand;
use App\Livewire\ScanArtikel;
use App\Livewire\LieferscheinVerarbeiten;
use App\Livewire\PspListen;
use App\Livewire\ArtikelListen;
use App\Livewire\UserVerwaltung;
use App\Livewire\ProtokolleListen;

use App\Http\Controllers\FileUploadController;


Route::view('/', 'welcome');

Route::get('bestand', ListBestand::class)
    ->middleware(['auth'])
    ->name('bestand');


Route::get('scanartikel', ScanArtikel::class)
    ->middleware(['auth', 'berechtigung:artikel buchen'])
    ->name('scanartikel');


Route::get('etikettenerstellen', EtikettenErstellen::class)
    ->middleware(['auth', 'berechtigung:warenzugang buchen'])
    ->name('etikettenerstellen');

Route::get('debitoren', DebitorListen::class)
    ->middleware(['auth', 'berechtigung:debitor anzeigen'])
    ->name('debitoren');

Route::get('psplisten', PspListen::class)
    ->middleware(['auth', 'berechtigung:psp anzeigen'])
    ->name('psp');

Route::get('artikellisten', ArtikelListen::class)
    ->middleware(['auth', 'berechtigung:artikel anzeigen'])
    ->name('artikel');

Route::get('lieferschein', LieferscheinVerarbeiten::class)
    ->middleware(['auth', 'berechtigung:warenzugang buchen'])
    ->name('lieferschein');

Route::get('mitarbeiter', UserVerwaltung::class)
    ->middleware(['auth', 'berechtigung:mitarbeiter anzeigen'])
    ->name('mitarbeiter');

Route::get('protokoll', ProtokolleListen::class)
    ->middleware(['auth', 'berechtigung:protokoll anzeigen'])
    ->name('protokoll');



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



Route::post('/upload', [FileUploadController::class, 'store']);
Route::delete('/upload/revert', [FileUploadController::class, 'revert']);

require __DIR__.'/auth.php';
