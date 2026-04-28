<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preventivo;
use App\Models\Commessa;
use App\Models\Fornitore;
use App\Models\RigaPreventivoProdotto;

class PreventivoController extends Controller
{
    public function index()
    {
        $preventivi = Preventivo::with('commessa.cliente')->get();
        return view('preventivi.index', compact('preventivi'));
    }

    public function create()
    {
        $commesse = Commessa::with('cliente')->get();
        return view('preventivi.create', compact('commesse'));
    }

    public function store(Request $request)
    {
        $prossimoNumero = Preventivo::max('id') + 1;

        Preventivo::create([
            'commessa_id' => $request->commessa_id,
            'numero' => 'PREV-' . date('Y') . '-' . str_pad($prossimoNumero, 4, '0', STR_PAD_LEFT),
            'descrizione' => $request->descrizione
        ]);

        return redirect('/preventivi');
    }

    public function show($id)
    {
        $preventivo = Preventivo::with(
            'commessa.cliente',
            'righeProdotti.servizi'
        )->findOrFail($id);

        return view('preventivi.show', compact('preventivo'));
    }

    public function aggiungiRigaProdotto(Request $request, $id)
    {
        $modalita = $request->modalita_calcolo;
        $quantita = (float) ($request->quantita ?? 1);

        $s1 = (float) ($request->sconto_fornitore_1 ?? 0);
        $s2 = (float) ($request->sconto_fornitore_2 ?? 0);
        $s3 = (float) ($request->sconto_fornitore_3 ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);

        $fattoreSconto = (1 - $s1/100) * (1 - $s2/100) * (1 - $s3/100);

        if ($modalita === 'da_listino') {
            $prezzoListino = (float) $request->prezzo_listino;
            $costoNetto = $prezzoListino * $fattoreSconto;
        } else {
            $costoNetto = (float) $request->costo_netto;
            $prezzoListino = $fattoreSconto > 0 ? $costoNetto / $fattoreSconto : 0;
        }

        $prezzoClienteUnitario = $costoNetto * (1 + $ricarico/100);

        // 👉 QUI ERA GIÀ GIUSTO (manteniamo)
        $totaleCliente = $prezzoClienteUnitario * $quantita;
        $totaleListino = $prezzoListino * $quantita;
        $totaleCosto = $costoNetto * $quantita;

        $scontoCliente = $prezzoListino > 0
            ? (($prezzoListino - $prezzoClienteUnitario) / $prezzoListino) * 100
            : 0;

        RigaPreventivoProdotto::create([
            'preventivo_id' => $id,
            'descrizione' => $request->descrizione,
            'modalita_calcolo' => $modalita,
            'quantita' => $quantita,
            'prezzo_listino' => $prezzoListino,
            'costo_netto' => $costoNetto,
            'prezzo_cliente_unitario' => $prezzoClienteUnitario,
            'totale_cliente' => $totaleCliente,
            'totale_listino' => $totaleListino,
            'totale_costo' => $totaleCosto,
            'sconto_cliente_percentuale' => $scontoCliente,
            'sconto_fornitore_1' => $s1,
            'sconto_fornitore_2' => $s2,
            'sconto_fornitore_3' => $s3,
            'ricarico_percentuale' => $ricarico
        ]);

        $this->aggiornaTotaliPreventivo($id);

        return redirect('/preventivi/'.$id);
    }

    private function aggiornaTotaliPreventivo($id)
    {
        $preventivo = Preventivo::with('righeProdotti.servizi')->findOrFail($id);

        // ✅ QUI ERA IL PROBLEMA
        // usare direttamente i totali già calcolati per riga

        $totaleProdotti = $preventivo->righeProdotti->sum('totale_cliente');
        $totaleListino = $preventivo->righeProdotti->sum('totale_listino');
        $totaleCostoProdotti = $preventivo->righeProdotti->sum('totale_costo');

        $totaleServizi = 0;
        $totaleCostoServizi = 0;

        foreach ($preventivo->righeProdotti as $riga) {
            $q = $riga->quantita ?? 1;

            $totaleServizi += $riga->servizi->sum('prezzo_cliente') * $q;
            $totaleCostoServizi += $riga->servizi->sum('costo_brc') * $q;
        }

        $totaleFinale = $totaleProdotti + $totaleServizi;
        $totaleCosto = $totaleCostoProdotti + $totaleCostoServizi;

        $preventivo->update([
            'totale_netto_prodotti' => $totaleProdotti,
            'totale_listino_prodotti' => $totaleListino,
            'totale_servizi_cliente' => $totaleServizi,
            'totale_cliente_finale' => $totaleFinale,
            'totale_costo_brc' => $totaleCosto,
            'utile_totale' => $totaleFinale - $totaleCosto
        ]);
    }
}