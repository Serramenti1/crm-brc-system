<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CommessaController;
use App\Http\Controllers\PreventivoController;
use App\Http\Controllers\RigaPreventivoProdottoController;
use App\Http\Controllers\RigaPreventivoServizioController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('clienti', ClienteController::class);
Route::resource('commesse', CommessaController::class);
Route::resource('preventivi', PreventivoController::class);
Route::resource('righe-preventivo-prodotti', RigaPreventivoProdottoController::class);

Route::post('/preventivi/{id}/aggiungi-riga-prodotto', [PreventivoController::class, 'aggiungiRigaProdotto']);

Route::post('/righe-prodotti/{riga_prodotto_id}/servizi', [RigaPreventivoServizioController::class, 'store']);

Route::put('/servizi-riga/{id}', [RigaPreventivoServizioController::class, 'update']);

Route::delete('/servizi-riga/{id}', [RigaPreventivoServizioController::class, 'destroy']);