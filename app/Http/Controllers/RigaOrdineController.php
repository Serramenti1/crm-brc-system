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