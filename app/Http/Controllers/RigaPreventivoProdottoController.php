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

        $dati = $this->calcolaRiga($request);

        $riga = RigaPreventivoProdotto::create([
            'preventivo_id' => $request->preventivo_id,
            'fornitore_id' => $request->fornitore_id,
            'descrizione' => $request->descrizione,
            'modalita_calcolo' => $dati['modalita'],
            'quantita' => $dati['quantita'],
            'prezzo_listino' => $dati['prezzoListino'],
            'sconto_fornitore_1' => $dati['s1'],
            'sconto_fornitore_2' => $dati['s2'],
            'sconto_fornitore_3' => $dati['s3'],
            'costo_netto' => $dati['costoNetto'],
            'ricarico_percentuale' => $dati['ricarico'],
            'prezzo_cliente_unitario' => $dati['prezzoClienteUnitario'],
            'sconto_cliente_percentuale' => $dati['scontoClientePercentuale'],
            'totale_listino' => $dati['totaleListino'],
            'totale_costo' => $dati['totaleCosto'],
            'totale_cliente' => $dati['totaleCliente'],
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

        $dati = $this->calcolaRiga($request);

        $riga->update([
            'fornitore_id' => $request->fornitore_id,
            'descrizione' => $request->descrizione,
            'modalita_calcolo' => $dati['modalita'],
            'quantita' => $dati['quantita'],
            'prezzo_listino' => $dati['prezzoListino'],
            'sconto_fornitore_1' => $dati['s1'],
            'sconto_fornitore_2' => $dati['s2'],
            'sconto_fornitore_3' => $dati['s3'],
            'costo_netto' => $dati['costoNetto'],
            'ricarico_percentuale' => $dati['ricarico'],
            'prezzo_cliente_unitario' => $dati['prezzoClienteUnitario'],
            'sconto_cliente_percentuale' => $dati['scontoClientePercentuale'],
            'totale_listino' => $dati['totaleListino'],
            'totale_costo' => $dati['totaleCosto'],
            'totale_cliente' => $dati['totaleCliente'],
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

        $riga->servizi()->delete();
        $riga->delete();

        $this->aggiornaTotaliPreventivo($preventivoId);

        return redirect('/preventivi/' . $preventivoId);
    }

    private function calcolaRiga(Request $request)
    {
        $modalita = $request->modalita_calcolo ?? 'da_listino';
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

        $scontoClientePercentuale = 0;
        if ($prezzoListino > 0) {
            $scontoClientePercentuale = (($prezzoListino - $prezzoClienteUnitario) / $prezzoListino) * 100;
        }

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
            'scontoClientePercentuale' => $scontoClientePercentuale,
            'totaleListino' => $prezzoListino * $quantita,
            'totaleCosto' => $costoNetto * $quantita,
            'totaleCliente' => $prezzoClienteUnitario * $quantita,
        ];
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