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
        $request->validate([
            'commessa_id' => 'required|exists:commesse,id',
            'numero' => 'nullable|string|max:255',
            'versione' => 'nullable|integer|min:1',
            'stato' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        Preventivo::create([
            'commessa_id' => $request->commessa_id,
            'numero' => $request->numero,
            'versione' => $request->versione ?? 1,
            'stato' => $request->stato ?? 'bozza',
            'totale_listino_prodotti' => 0,
            'totale_netto_prodotti' => 0,
            'totale_servizi_cliente' => 0,
            'totale_cliente_finale' => 0,
            'sconto_medio_cliente' => 0,
            'totale_costo_brc' => 0,
            'utile_totale' => 0,
            'note' => $request->note,
        ]);

        return redirect('/preventivi');
    }

    public function show($id)
    {
        $preventivo = Preventivo::with('commessa.cliente', 'righeProdotti.fornitore')->findOrFail($id);
        $fornitori = Fornitore::all();

        return view('preventivi.show', compact('preventivo', 'fornitori'));
    }

    public function aggiungiRigaProdotto(Request $request, $id)
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

        $preventivo = Preventivo::findOrFail($id);

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

        RigaPreventivoProdotto::create([
            'preventivo_id' => $preventivo->id,
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

        $this->aggiornaTotaliPreventivo($preventivo->id);

        return redirect('/preventivi/' . $preventivo->id);
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