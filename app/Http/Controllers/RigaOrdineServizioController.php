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
    $ordine = Ordine::with(
        'righe.servizi',
        'commessa.tipoIntervento.ivaPrincipale',
        'commessa.tipoIntervento.ivaSecondaria'
    )->findOrFail($ordineId);

    $preventivoFake = new \stdClass();
    $preventivoFake->commessa = $ordine->commessa;
    $preventivoFake->righeProdotti = $ordine->righe->map(function ($riga) {
        $rigaFake = new \stdClass();
        $rigaFake->quantita = $riga->quantita;
        $rigaFake->totale_cliente = $riga->totale_cliente;
        $rigaFake->totale_costo = $riga->totale_costo;
        $rigaFake->bene_significativo = $riga->bene_significativo;
        $rigaFake->servizi = $riga->servizi;
        return $rigaFake;
    });

    $calcoloIvaService = new \App\Services\CalcoloIvaService();
    $calcoloIva = $calcoloIvaService->calcolaDaPreventivo($preventivoFake);

    $ordine->update([
        'imponibile'      => $calcoloIva['totale_cliente'],
        'imponibile_4'    => $calcoloIva['imponibile_4'],
        'imponibile_10'   => $calcoloIva['imponibile_10'],
        'imponibile_22'   => $calcoloIva['imponibile_22'],
        'iva_4'           => $calcoloIva['iva_4'],
        'iva_10'          => $calcoloIva['iva_10'],
        'iva_22'          => $calcoloIva['iva_22'],
        'totale_iva'      => $calcoloIva['totale_iva'],
        'totale_con_iva'  => $calcoloIva['totale_con_iva'],
    ]);
}
}