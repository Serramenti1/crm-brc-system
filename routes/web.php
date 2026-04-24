<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CommessaController;
use App\Http\Controllers\PreventivoController;
use App\Http\Controllers\RigaPreventivoProdottoController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('clienti', ClienteController::class);
Route::resource('commesse', CommessaController::class);
Route::resource('preventivi', PreventivoController::class);
Route::resource('righe-preventivo-prodotti', RigaPreventivoProdottoController::class);

// rotta per aggiungere riga prodotto dentro un preventivo
Route::post('/preventivi/{id}/aggiungi-riga-prodotto', [PreventivoController::class, 'aggiungiRigaProdotto']);