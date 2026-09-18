<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RigaOrdine;
use App\Models\RigaDettaglioOrdine;

class RigaDettaglioOrdineController extends Controller
{
    public function creaTabella(Request $request, $rigaOrdineId)
    {
        $request->validate([
            'numero_righe' => 'required|integer|min:1|max:50',
        ]);

        $riga = RigaOrdine::with('ordine')->findOrFail($rigaOrdineId);
        $ordine = $riga->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi gestire il dettaglio solo in preparazione contratto.');
        }

        for ($i = 1; $i <= $request->numero_righe; $i++) {
            RigaDettaglioOrdine::create([
                'riga_ordine_id' => $riga->id,
                'numero_progressivo' => $i,
                'pezzi' => 1,
            ]);
        }

        return redirect('/ordini/' . $ordine->id)
    ->with('success', 'Tabella dettaglio creata.')
    ->with('apri_dettaglio_riga', $riga->id);
    }

    public function aggiornaRiga(Request $request, $id)
    {
        $request->validate([
            'posizione' => 'nullable|string|max:255',
            'descrizione' => 'nullable|string|max:255',
            'pezzi' => 'nullable|numeric|min:0',
            'costo_listino' => 'nullable|numeric|min:0',
            'sconto_1' => 'nullable|numeric|min:0|max:100',
            'sconto_2' => 'nullable|numeric|min:0|max:100',
            'sconto_3' => 'nullable|numeric|min:0|max:100',
            'trasporto' => 'nullable|numeric|min:0',
            'posa' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
        ]);

        $rigaDettaglio = RigaDettaglioOrdine::with('rigaOrdine.ordine')->findOrFail($id);
        $ordine = $rigaDettaglio->rigaOrdine->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi modificare il dettaglio solo in preparazione contratto.');
        }

        $rigaDettaglio->update([
            'posizione' => $request->posizione,
            'descrizione' => $request->descrizione,
            'pezzi' => $request->pezzi ?? 1,
            'costo_listino' => $request->costo_listino ?? 0,
            'sconto_1' => $request->sconto_1 ?? 0,
            'sconto_2' => $request->sconto_2 ?? 0,
            'sconto_3' => $request->sconto_3 ?? 0,
            'trasporto' => $request->trasporto ?? 0,
            'posa' => $request->posa ?? 0,
            'ricarico_percentuale' => $request->ricarico_percentuale ?? 0,
        ]);

        return redirect('/ordini/' . $ordine->id)
    ->with('success', 'Riga dettaglio aggiornata.')
    ->with('apri_dettaglio_riga', $rigaDettaglio->riga_ordine_id);
    }

    public function aggiungiRiga($rigaOrdineId)
    {
        $riga = RigaOrdine::with('ordine')->findOrFail($rigaOrdineId);
        $ordine = $riga->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi gestire il dettaglio solo in preparazione contratto.');
        }

        $ultimoNumero = RigaDettaglioOrdine::where('riga_ordine_id', $riga->id)->max('numero_progressivo');

        RigaDettaglioOrdine::create([
            'riga_ordine_id' => $riga->id,
            'numero_progressivo' => ($ultimoNumero ?? 0) + 1,
            'pezzi' => 1,
        ]);

        return redirect('/ordini/' . $ordine->id)
    ->with('success', 'Riga aggiunta.')
    ->with('apri_dettaglio_riga', $riga->id);
    }

    public function eliminaRiga($id)
    {
        $rigaDettaglio = RigaDettaglioOrdine::with('rigaOrdine.ordine')->findOrFail($id);
        $ordine = $rigaDettaglio->rigaOrdine->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi gestire il dettaglio solo in preparazione contratto.');
        }

        $rigaDettaglio->delete();

        return redirect('/ordini/' . $ordine->id)
    ->with('success', 'Riga eliminata.')
    ->with('apri_dettaglio_riga', $rigaDettaglio->riga_ordine_id);
    }
}