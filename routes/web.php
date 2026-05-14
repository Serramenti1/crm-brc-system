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
use App\Http\Controllers\ImpostazioneController;
use App\Models\Ordine;
use App\Http\Controllers\RigaOrdineController;
use App\Http\Controllers\RigaOrdineServizioController;

Route::get('/', function () {
    $conteggiOrdini = [
        'in_lavorazione' => Ordine::where('stato', 'in_lavorazione')->count(),
        'completo_attesa_merce' => Ordine::where('stato', 'completo_attesa_merce')->count(),
        'attesa_saldo_merce' => Ordine::where('stato', 'attesa_saldo_merce')->count(),
        'programmare_posa' => Ordine::where('stato', 'programmare_posa')->count(),
    ];

    return view('dashboard', compact('conteggiOrdini'));
});

Route::resource('clienti', ClienteController::class);
Route::resource('commesse', CommessaController::class);
Route::get('/preventivi/{id}/visualizza', [PreventivoController::class, 'visualizza']);
Route::resource('preventivi', PreventivoController::class);
Route::resource('righe-preventivo-prodotti', RigaPreventivoProdottoController::class);
Route::resource('prodotti-fornitore', ProdottoFornitoreController::class);
Route::resource('fornitori', FornitoreController::class);

Route::get('/ordini', [OrdineController::class, 'index']);
Route::get('/ordini-completi', [OrdineController::class, 'completi']);
Route::get('/ordini/stato/{stato}', [OrdineController::class, 'perStato'])->name('ordini.perStato');
Route::get('/ordini/{id}/visualizza', [OrdineController::class, 'visualizza']);
Route::get('/ordini/{id}', [OrdineController::class, 'show']);
Route::delete('/ordini/{id}', [OrdineController::class, 'destroy']);

Route::post('/preventivi/{id}/crea-ordine', [OrdineController::class, 'creaDaPreventivo']);
Route::post('/righe-ordine/{id}/aggiorna', [OrdineController::class, 'aggiornaRiga']);

Route::post('/preventivi/{id}/aggiungi-riga-prodotto', [PreventivoController::class, 'aggiungiRigaProdotto']);

Route::post('/righe-prodotti/{riga_prodotto_id}/servizi', [RigaPreventivoServizioController::class, 'store']);
Route::post('/ordini/{id}/aggiorna-stato-avanzato', [OrdineController::class, 'aggiornaStatoAvanzato'])
    ->name('ordini.aggiornaStatoAvanzato');

Route::put('/servizi-riga/{id}', [RigaPreventivoServizioController::class, 'update']);
Route::delete('/servizi-riga/{id}', [RigaPreventivoServizioController::class, 'destroy']);

Route::get('/impostazioni', [ImpostazioneController::class, 'index']);
Route::get('/impostazioni/iva', [ImpostazioneController::class, 'iva']);
Route::post('/impostazioni/iva', [ImpostazioneController::class, 'storeIva']);
Route::put('/impostazioni/iva/{id}', [ImpostazioneController::class, 'updateIva']);

Route::get('/impostazioni/detrazioni', [ImpostazioneController::class, 'detrazioni']);
Route::post('/impostazioni/detrazioni', [ImpostazioneController::class, 'storeDetrazione']);
Route::put('/impostazioni/detrazioni/{id}', [ImpostazioneController::class, 'updateDetrazione']);

Route::get('/impostazioni/servizi', [ImpostazioneController::class, 'servizi']);
Route::post('/impostazioni/servizi', [ImpostazioneController::class, 'storeServizio']);
Route::put('/impostazioni/servizi/{id}', [ImpostazioneController::class, 'updateServizio']);
Route::post('/ordini/{id}/stato-precedente', [OrdineController::class, 'tornaStatoPrecedente'])
    ->name('ordini.tornaStatoPrecedente');
Route::put('/righe-ordine-prodotto/{id}', [RigaOrdineController::class, 'update']);
Route::delete('/righe-ordine-prodotto/{id}', [RigaOrdineController::class, 'destroy']);
Route::post('/ordini/{ordineId}/righe-prodotti', [RigaOrdineController::class, 'store']);

Route::post('/righe-ordine/{rigaOrdineId}/servizi', [RigaOrdineServizioController::class, 'store']);
Route::put('/servizi-riga-ordine/{id}', [RigaOrdineServizioController::class, 'update']);
Route::delete('/servizi-riga-ordine/{id}', [RigaOrdineServizioController::class, 'destroy']);   
Route::get('/impostazioni/tipi-intervento', [ImpostazioneController::class, 'tipiIntervento']);
Route::post('/impostazioni/tipi-intervento', [ImpostazioneController::class, 'storeTipoIntervento']);
Route::put('/impostazioni/tipi-intervento/{id}', [ImpostazioneController::class, 'updateTipoIntervento']);
