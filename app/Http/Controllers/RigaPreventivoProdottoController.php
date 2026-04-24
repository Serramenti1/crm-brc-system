<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RigaPreventivoProdotto;
use App\Models\Preventivo;
use App\Models\Fornitore;

class RigaPreventivoProdottoController extends Controller
{
    public function index()
    {
        $righe = RigaPreventivoProdotto::with('preventivo.commessa.cliente', 'fornitore')->get();
        return view('righe_preventivo_prodotti.index', compact('righe'));
    }

    public function create()
    {
        $preventivi = Preventivo::with('commessa.cliente')->get();
        $fornitori = Fornitore::all();

        return view('righe_preventivo_prodotti.create', compact('preventivi', 'fornitori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'preventivo_id' => 'required|exists:preventivi,id',
            'fornitore_id' => 'nullable|exists:fornitori,id',
            'descrizione' => 'required|string|max:255',
            'modalita_calcolo' => 'required|in:da_listino,da_costo_netto',
            'quantita' => 'nullable|numeric|min:0',
            'prezzo_listino' => 'nullable|numeric|min:0',
            'sconto_fornitore_1' => 'nullable|numeric|min:0|max:100',
            'sconto_fornitore_2' => 'nullable|numeric|min:0|max:100',
            'sconto_fornitore_3' => 'nullable|numeric|min:0|max:100',
            'costo_netto' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'ordine_visualizzazione' => 'nullable|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $modalita = $request->modalita_calcolo ?? 'da_listino';
        $quantita = (float) ($request->quantita ?? 1);

        $s1 = (float) ($request->sconto_fornitore_1 ?? 0);
        $s2 = (float) ($request->sconto_fornitore_2 ?? 0);
        $s3 = (float) ($request->sconto_fornitore_3 ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);

        $fattoreSconto = (1 - ($s1 / 100)) * (1 - ($s2 / 100)) * (1 - ($s3 / 100));

        $prezzoListino = 0;
        $costoNetto = 0;

        if ($modalita === 'da_listino') {
            $prezzoListino = (float) ($request->prezzo_listino ?? 0);
            $costoNetto = $prezzoListino * $fattoreSconto;
        } else {
            $costoNetto = (float) ($request->costo_netto ?? 0);

            if ($fattoreSconto > 0) {
                $prezzoListino = $costoNetto / $fattoreSconto;
            } else {
                $prezzoListino = 0;
            }
        }

        $prezzoClienteUnitario = $costoNetto * (1 + ($ricarico / 100));

        $scontoClientePercentuale = 0;
        if ($prezzoListino > 0) {
            $scontoClientePercentuale = (($prezzoListino - $prezzoClienteUnitario) / $prezzoListino) * 100;
        }

        $totaleListino = $prezzoListino * $quantita;
        $totaleCosto = $costoNetto * $quantita;
        $totaleCliente = $prezzoClienteUnitario * $quantita;

        $riga = RigaPreventivoProdotto::create([
            'preventivo_id' => $request->preventivo_id,
            'fornitore_id' => $request->fornitore_id,
            'descrizione' => $request->descrizione,
            'modalita_calcolo' => $modalita,
            'quantita' => $quantita,
            'prezzo_listino' => $prezzoListino,
            'sconto_fornitore_1' => $s1,
            'sconto_fornitore_2' => $s2,
            'sconto_fornitore_3' => $s3,
            'costo_netto' => $costoNetto,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente_unitario' => $prezzoClienteUnitario,
            'sconto_cliente_percentuale' => $scontoClientePercentuale,
            'totale_listino' => $totaleListino,
            'totale_costo' => $totaleCosto,
            'totale_cliente' => $totaleCliente,
            'ordine_visualizzazione' => $request->ordine_visualizzazione ?? 0,
            'note' => $request->note,
        ]);

        $this->aggiornaTotaliPreventivo($riga->preventivo_id);

        return redirect('/preventivi/' . $riga->preventivo_id);
    }

    public function edit($id)
    {
        $riga = RigaPreventivoProdotto::findOrFail($id);
        $fornitori = Fornitore::all();

        return view('righe_preventivo_prodotti.edit', compact('riga', 'fornitori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fornitore_id' => 'nullable|exists:fornitori,id',
            'descrizione' => 'required|string|max:255',
            'modalita_calcolo' => 'required|in:da_listino,da_costo_netto',
            'quantita' => 'nullable|numeric|min:0',
            'prezzo_listino' => 'nullable|numeric|min:0',
            'sconto_fornitore_1' => 'nullable|numeric|min:0|max:100',
            'sconto_fornitore_2' => 'nullable|numeric|min:0|max:100',
            'sconto_fornitore_3' => 'nullable|numeric|min:0|max:100',
            'costo_netto' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'ordine_visualizzazione' => 'nullable|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $riga = RigaPreventivoProdotto::findOrFail($id);

        $modalita = $request->modalita_calcolo ?? 'da_listino';
        $quantita = (float) ($request->quantita ?? 1);

        $s1 = (float) ($request->sconto_fornitore_1 ?? 0);
        $s2 = (float) ($request->sconto_fornitore_2 ?? 0);
        $s3 = (float) ($request->sconto_fornitore_3 ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);

        $fattoreSconto = (1 - ($s1 / 100)) * (1 - ($s2 / 100)) * (1 - ($s3 / 100));

        $prezzoListino = 0;
        $costoNetto = 0;

        if ($modalita === 'da_listino') {
            $prezzoListino = (float) ($request->prezzo_listino ?? 0);
            $costoNetto = $prezzoListino * $fattoreSconto;
        } else {
            $costoNetto = (float) ($request->costo_netto ?? 0);

            if ($fattoreSconto > 0) {
                $prezzoListino = $costoNetto / $fattoreSconto;
            } else {
                $prezzoListino = 0;
            }
        }

        $prezzoClienteUnitario = $costoNetto * (1 + ($ricarico / 100));

        $scontoClientePercentuale = 0;
        if ($prezzoListino > 0) {
            $scontoClientePercentuale = (($prezzoListino - $prezzoClienteUnitario) / $prezzoListino) * 100;
        }

        $totaleListino = $prezzoListino * $quantita;
        $totaleCosto = $costoNetto * $quantita;
        $totaleCliente = $prezzoClienteUnitario * $quantita;

        $riga->update([
            'fornitore_id' => $request->fornitore_id,
            'descrizione' => $request->descrizione,
            'modalita_calcolo' => $modalita,
            'quantita' => $quantita,
            'prezzo_listino' => $prezzoListino,
            'sconto_fornitore_1' => $s1,
            'sconto_fornitore_2' => $s2,
            'sconto_fornitore_3' => $s3,
            'costo_netto' => $costoNetto,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente_unitario' => $prezzoClienteUnitario,
            'sconto_cliente_percentuale' => $scontoClientePercentuale,
            'totale_listino' => $totaleListino,
            'totale_costo' => $totaleCosto,
            'totale_cliente' => $totaleCliente,
            'ordine_visualizzazione' => $request->ordine_visualizzazione ?? 0,
            'note' => $request->note,
        ]);

        $this->aggiornaTotaliPreventivo($riga->preventivo_id);

        return redirect('/preventivi/' . $riga->preventivo_id);
    }

    public function destroy($id)
    {
        $riga = RigaPreventivoProdotto::findOrFail($id);
        $preventivoId = $riga->preventivo_id;

        $riga->delete();

        $this->aggiornaTotaliPreventivo($preventivoId);

        return redirect('/preventivi/' . $preventivoId);
    }

    private function aggiornaTotaliPreventivo($preventivoId)
    {
        $preventivo = Preventivo::with('righeProdotti')->findOrFail($preventivoId);

        $totaleListinoProdotti = $preventivo->righeProdotti->sum('totale_listino');
        $totaleNettoProdotti = $preventivo->righeProdotti->sum('totale_cliente');
        $totaleCostoBrc = $preventivo->righeProdotti->sum('totale_costo');

        $totaleServiziCliente = 0;

        $totaleClienteFinale = $totaleNettoProdotti + $totaleServiziCliente;
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