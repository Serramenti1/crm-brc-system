<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RigaOrdine;
use App\Models\Ordine;

class RigaOrdineController extends Controller
{
    public function store(Request $request, $ordineId)
{
    $request->validate([
        'fornitore_id' => 'nullable|exists:fornitori,id',
        'descrizione' => 'required|string|max:255',
        'modalita_calcolo' => 'required|in:da_listino,da_costo_netto',
        'quantita' => 'nullable|numeric|min:0',
        'prezzo_listino' => 'nullable|numeric|min:0',
        'costo_netto' => 'nullable|numeric|min:0',
        'sconto_fornitore_1' => 'nullable|numeric|min:0|max:100',
        'sconto_fornitore_2' => 'nullable|numeric|min:0|max:100',
        'sconto_fornitore_3' => 'nullable|numeric|min:0|max:100',
        'ricarico_percentuale' => 'nullable|numeric|min:0',
        'bene_significativo' => 'nullable|boolean',
        'note' => 'nullable|string',
    ]);

    $ordine = Ordine::findOrFail($ordineId);

    if ($ordine->stato != 'preparazione_contratto') {
        return redirect('/ordini/' . $ordine->id)
            ->with('error', 'Puoi aggiungere prodotti solo in preparazione contratto.');
    }

    $dati = $this->calcolaRiga($request);

    RigaOrdine::create([
        'ordine_id' => $ordine->id,
        'fornitore_id' => $request->fornitore_id,
        'descrizione' => $request->descrizione,
        'quantita' => $dati['quantita'],
        'imponibile' => $dati['totaleCliente'],
        'modalita_calcolo' => $dati['modalita'],
        'prezzo_listino' => $dati['prezzoListino'],
        'costo_netto' => $dati['costoNetto'],
        'sconto_fornitore_1' => $dati['s1'],
        'sconto_fornitore_2' => $dati['s2'],
        'sconto_fornitore_3' => $dati['s3'],
        'ricarico_percentuale' => $dati['ricarico'],
        'bene_significativo' => $request->has('bene_significativo') ? 1 : 0,
        'prezzo_cliente_unitario' => $dati['prezzoClienteUnitario'],
        'totale_cliente' => $dati['totaleCliente'],
        'totale_costo' => $dati['totaleCosto'],
        'note' => $request->note,
        'inviato' => false,
        'co_ricevuta' => false,
        'in_produzione' => false,
        'merce_arrivata' => false,
    ]);

    $this->aggiornaTotaliOrdine($ordine->id);

    return redirect('/ordini/' . $ordine->id)
        ->with('success', 'Prodotto aggiunto correttamente.');
}

    public function update(Request $request, $id)
    {
        $request->validate([
    'descrizione' => 'nullable|string|max:255',
    'modalita_calcolo' => 'required|in:da_listino,da_costo_netto',
    'quantita' => 'nullable|numeric|min:0',
            'prezzo_listino' => 'nullable|numeric|min:0',
            'costo_netto' => 'nullable|numeric|min:0',
            'sconto_fornitore_1' => 'nullable|numeric|min:0|max:100',
            'sconto_fornitore_2' => 'nullable|numeric|min:0|max:100',
            'sconto_fornitore_3' => 'nullable|numeric|min:0|max:100',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'bene_significativo' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        $riga = RigaOrdine::with('ordine')->findOrFail($id);
        $ordine = $riga->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi modificare le righe solo in preparazione contratto.');
        }

        $dati = $this->calcolaRiga($request);

        $riga->update([
    'descrizione' => $request->descrizione ?? $riga->descrizione,
    'modalita_calcolo' => $dati['modalita'],
    'quantita' => $dati['quantita'],
            'prezzo_listino' => $dati['prezzoListino'],
            'costo_netto' => $dati['costoNetto'],
            'sconto_fornitore_1' => $dati['s1'],
            'sconto_fornitore_2' => $dati['s2'],
            'sconto_fornitore_3' => $dati['s3'],
            'ricarico_percentuale' => $dati['ricarico'],
            'bene_significativo' => $request->has('bene_significativo') ? 1 : 0,
            'prezzo_cliente_unitario' => $dati['prezzoClienteUnitario'],
            'totale_cliente' => $dati['totaleCliente'],
            'totale_costo' => $dati['totaleCosto'],
            'imponibile' => $dati['totaleCliente'],
            'note' => $request->note,
        ]);

        $this->aggiornaTotaliOrdine($ordine->id);

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Riga ordine aggiornata correttamente.');
    }

    public function destroy($id)
    {
        $riga = RigaOrdine::with('ordine', 'servizi')->findOrFail($id);
        $ordine = $riga->ordine;

        if ($ordine->stato != 'preparazione_contratto') {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Puoi eliminare righe solo in preparazione contratto.');
        }

        $riga->servizi()->delete();
        $riga->delete();

        $this->aggiornaTotaliOrdine($ordine->id);

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Riga ordine eliminata correttamente.');
    }

    private function calcolaRiga(Request $request)
    {
        $modalita = $request->modalita_calcolo;
        $quantita = (float) ($request->quantita ?? 1);

        $s1 = (float) ($request->sconto_fornitore_1 ?? 0);
        $s2 = (float) ($request->sconto_fornitore_2 ?? 0);
        $s3 = (float) ($request->sconto_fornitore_3 ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);

        $fattoreSconto = (1 - ($s1 / 100)) * (1 - ($s2 / 100)) * (1 - ($s3 / 100));

        if ($modalita === 'da_listino') {
            $prezzoListino = (float) ($request->prezzo_listino ?? 0);
            $costoNetto = $prezzoListino * $fattoreSconto;
        } else {
            $costoNetto = (float) ($request->costo_netto ?? 0);
            $prezzoListino = $fattoreSconto > 0 ? $costoNetto / $fattoreSconto : 0;
        }

        $prezzoClienteUnitario = $costoNetto * (1 + ($ricarico / 100));

        return [
            'modalita' => $modalita,
            'quantita' => $quantita,
            's1' => $s1,
            's2' => $s2,
            's3' => $s3,
            'ricarico' => $ricarico,
            'prezzoListino' => $prezzoListino,
            'costoNetto' => $costoNetto,
            'prezzoClienteUnitario' => $prezzoClienteUnitario,
            'totaleCliente' => $prezzoClienteUnitario * $quantita,
            'totaleCosto' => $costoNetto * $quantita,
        ];
    }

    private function aggiornaTotaliOrdine($ordineId)
{
    $ordine = Ordine::with(
        'righe.servizi',
        'commessa.tipoIntervento.ivaPrincipale',
        'commessa.tipoIntervento.ivaSecondaria'
    )->findOrFail($ordineId);

    // Costruisco un oggetto compatibile con CalcoloIvaService
    // che si aspetta un preventivo con righeProdotti
    // Uso l'ordine stesso mappando le righe ordine
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