<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RigaPreventivoServizio;
use App\Models\RigaPreventivoProdotto;
use App\Models\Preventivo;

class RigaPreventivoServizioController extends Controller
{
    // CREA SERVIZIO
    public function store(Request $request, $riga_prodotto_id)
    {
        $request->validate([
            'tipo_servizio' => 'required|string|max:255',
            'costo_brc' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
        ]);

        $riga = RigaPreventivoProdotto::findOrFail($riga_prodotto_id);

        $costo = (float) ($request->costo_brc ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);

        $prezzo = $costo * (1 + ($ricarico / 100));

        RigaPreventivoServizio::create([
            'riga_prodotto_id' => $riga->id,
            'tipo_servizio' => $request->tipo_servizio,
            'costo_brc' => $costo,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente' => $prezzo,
        ]);

        $this->aggiornaTotaliPreventivo($riga->preventivo_id);

        return redirect('/preventivi/' . $riga->preventivo_id);
    }

    // MODIFICA SERVIZIO
    public function update(Request $request, $id)
    {
        $servizio = RigaPreventivoServizio::findOrFail($id);

        $request->validate([
            'tipo_servizio' => 'required|string|max:255',
            'costo_brc' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
        ]);

        $costo = (float) ($request->costo_brc ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);

        $prezzo = $costo * (1 + ($ricarico / 100));

        $servizio->update([
            'tipo_servizio' => $request->tipo_servizio,
            'costo_brc' => $costo,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente' => $prezzo,
        ]);

        $this->aggiornaTotaliPreventivo($servizio->rigaProdotto->preventivo_id);

        return redirect('/preventivi/' . $servizio->rigaProdotto->preventivo_id);
    }

    // ELIMINA SERVIZIO
    public function destroy($id)
    {
        $servizio = RigaPreventivoServizio::findOrFail($id);
        $preventivoId = $servizio->rigaProdotto->preventivo_id;

        $servizio->delete();

        $this->aggiornaTotaliPreventivo($preventivoId);

        return redirect('/preventivi/' . $preventivoId);
    }

    // AGGIORNA TOTALI (CORRETTO CON QUANTITÀ)
    private function aggiornaTotaliPreventivo($preventivoId)
    {
        $preventivo = Preventivo::with('righeProdotti.servizi')->findOrFail($preventivoId);

        $totaleListinoProdotti = $preventivo->righeProdotti->sum('totale_listino');
        $totaleNettoProdotti = $preventivo->righeProdotti->sum('totale_cliente');
        $totaleCostoProdotti = $preventivo->righeProdotti->sum('totale_costo');

        $totaleServiziCliente = 0;
        $totaleCostoServizi = 0;

        foreach ($preventivo->righeProdotti as $riga) {
            $quantita = (float) ($riga->quantita ?? 1);

            $totaleServiziCliente += $riga->servizi->sum('prezzo_cliente') * $quantita;
            $totaleCostoServizi += $riga->servizi->sum('costo_brc') * $quantita;
        }

        $totaleClienteFinale = $totaleNettoProdotti + $totaleServiziCliente;
        $totaleCostoBrc = $totaleCostoProdotti + $totaleCostoServizi;
        $utileTotale = $totaleClienteFinale - $totaleCostoBrc;

        $scontoMedioCliente = 0;
        if ($totaleListinoProdotti > 0) {
            $scontoMedioCliente = (($totaleListinoProdotti - $totaleNettoProdotti) / $totaleListinoProdotti) * 100;
        }

        $preventivo->update([
            'totale_listino_prodotti' => $totaleListinoProdotti,
            'totale_netto_prodotti' => $totaleNettoProdotti,
            'totale_servizi_cliente' => $totaleServiziCliente,
            'totale_cliente_finale' => $totaleClienteFinale,
            'sconto_medio_cliente' => $scontoMedioCliente,
            'totale_costo_brc' => $totaleCostoBrc,
            'utile_totale' => $utileTotale,
        ]);
    }
}