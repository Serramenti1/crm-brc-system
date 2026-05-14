<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RigaOrdine;
use App\Models\RigaOrdineServizio;
use App\Models\Ordine;

class RigaOrdineServizioController extends Controller
{
    public function store(Request $request, $rigaOrdineId)
    {
        $request->validate([
            'tipo_servizio' => 'required|string|max:255',
            'descrizione' => 'nullable|string|max:255',
            'costo_brc' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $riga = RigaOrdine::with('ordine')->findOrFail($rigaOrdineId);
        $ordine = $riga->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi aggiungere servizi solo in preparazione contratto.');
        }

        $costo = (float) ($request->costo_brc ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);
        $prezzoCliente = $costo * (1 + ($ricarico / 100));

        RigaOrdineServizio::create([
            'riga_ordine_id' => $riga->id,
            'tipo_servizio' => $request->tipo_servizio,
            'descrizione' => $request->descrizione,
            'costo_brc' => $costo,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente' => $prezzoCliente,
            'note' => $request->note,
        ]);

        $this->aggiornaTotaliOrdine($ordine->id);

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Servizio aggiunto correttamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_servizio' => 'required|string|max:255',
            'descrizione' => 'nullable|string|max:255',
            'costo_brc' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $servizio = RigaOrdineServizio::with('rigaOrdine.ordine')->findOrFail($id);
        $ordine = $servizio->rigaOrdine->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi modificare servizi solo in preparazione contratto.');
        }

        $costo = (float) ($request->costo_brc ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);
        $prezzoCliente = $costo * (1 + ($ricarico / 100));

        $servizio->update([
            'tipo_servizio' => $request->tipo_servizio,
            'descrizione' => $request->descrizione,
            'costo_brc' => $costo,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente' => $prezzoCliente,
            'note' => $request->note,
        ]);

        $this->aggiornaTotaliOrdine($ordine->id);

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Servizio aggiornato correttamente.');
    }

    public function destroy($id)
    {
        $servizio = RigaOrdineServizio::with('rigaOrdine.ordine')->findOrFail($id);
        $ordine = $servizio->rigaOrdine->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi eliminare servizi solo in preparazione contratto.');
        }

        $servizio->delete();

        $this->aggiornaTotaliOrdine($ordine->id);

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Servizio eliminato correttamente.');
    }

    private function aggiornaTotaliOrdine($ordineId)
    {
        $ordine = Ordine::with('righe.servizi')->findOrFail($ordineId);

        $totaleImponibile = 0;

        foreach ($ordine->righe as $riga) {
            $quantita = (float) ($riga->quantita ?? 1);

            $totaleImponibile += (float) $riga->totale_cliente;

            foreach ($riga->servizi as $servizio) {
                $totaleImponibile += (float) $servizio->prezzo_cliente * $quantita;
            }
        }

        $totaleIva = $ordine->totale_iva ?? 0;

        $ordine->update([
            'imponibile' => $totaleImponibile,
            'totale_con_iva' => $totaleImponibile + $totaleIva,
        ]);
    }
}