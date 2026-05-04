<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CommessaController;
use App\Http\Controllers\PreventivoController;
use App\Http\Controllers\RigaPreventivoProdottoController;
use App\Http\Controllers\RigaPreventivoServizioController;
use App\Http\Controllers\ProdottoFornitoreController;
use App\Http\Controllers\FornitoreController;
use App\Http\Controllers\OrdineController;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('clienti', ClienteController::class);
Route::resource('commesse', CommessaController::class);
Route::resource('preventivi', PreventivoController::class);
Route::resource('righe-preventivo-prodotti', RigaPreventivoProdottoController::class);
Route::resource('prodotti-fornitore', ProdottoFornitoreController::class);
Route::resource('fornitori', FornitoreController::class);

Route::get('/ordini', [OrdineController::class, 'index']);
Route::get('/ordini-completi', [OrdineController::class, 'completi']);
Route::get('/ordini/{id}', [OrdineController::class, 'show']);
Route::delete('/ordini/{id}', [OrdineController::class, 'destroy']);

Route::post('/preventivi/{id}/crea-ordine', [OrdineController::class, 'creaDaPreventivo']);
Route::post('/righe-ordine/{id}/aggiorna', [OrdineController::class, 'aggiornaRiga']);

Route::post('/preventivi/{id}/aggiungi-riga-prodotto', [PreventivoController::class, 'aggiungiRigaProdotto']);

Route::post('/righe-prodotti/{riga_prodotto_id}/servizi', [RigaPreventivoServizioController::class, 'store']);

Route::put('/servizi-riga/{id}', [RigaPreventivoServizioController::class, 'update']);

Route::delete('/servizi-riga/{id}', [RigaPreventivoServizioController::class, 'destroy']);