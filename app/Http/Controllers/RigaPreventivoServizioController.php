<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RigaPreventivoServizio;
use App\Models\RigaPreventivoProdotto;
use App\Models\Preventivo;

class RigaPreventivoServizioController extends Controller
{
    public function store(Request $request, $riga_prodotto_id)
    {
        $request->validate([
            'tipo_servizio' => 'required|string|max:255',
            'descrizione' => 'nullable|string|max:255',
            'costo_brc' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $riga = RigaPreventivoProdotto::findOrFail($riga_prodotto_id);

        $costoBrc = (float) ($request->costo_brc ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);

        $prezzoCliente = $costoBrc * (1 + ($ricarico / 100));

        RigaPreventivoServizio::create([
            'riga_prodotto_id' => $riga->id,
            'tipo_servizio' => $request->tipo_servizio,
            'descrizione' => $request->descrizione,
            'costo_brc' => $costoBrc,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente' => $prezzoCliente,
            'note' => $request->note,
        ]);

        $this->aggiornaTotaliPreventivo($riga->preventivo_id);

        return redirect('/preventivi/' . $riga->preventivo_id);
    }

    public function destroy($id)
    {
        $servizio = RigaPreventivoServizio::findOrFail($id);
        $preventivoId = $servizio->rigaProdotto->preventivo_id;

        $servizio->delete();

        $this->aggiornaTotaliPreventivo($preventivoId);

        return redirect('/preventivi/' . $preventivoId);
    }

    private function aggiornaTotaliPreventivo($preventivoId)
    {
        $preventivo = Preventivo::with('righeProdotti.servizi')->findOrFail($preventivoId);

        $totaleListinoProdotti = $preventivo->righeProdotti->sum('totale_listino');
        $totaleNettoProdotti = $preventivo->righeProdotti->sum('totale_cliente');
        $totaleCostoProdotti = $preventivo->righeProdotti->sum('totale_costo');

        $totaleServiziCliente = 0;
        $totaleCostoServizi = 0;

        foreach ($preventivo->righeProdotti as $riga) {
            $totaleServiziCliente += $riga->servizi->sum('prezzo_cliente');
            $totaleCostoServizi += $riga->servizi->sum('costo_brc');
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