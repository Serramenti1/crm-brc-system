<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preventivo;
use App\Models\Commessa;
use App\Models\Fornitore;
use App\Models\ProdottoFornitore;
use App\Models\RigaPreventivoProdotto;

class PreventivoController extends Controller
{
    public function index(Request $request)
    {
        $query = Preventivo::with('commessa.cliente');

        if ($request->filled('cliente')) {
            $parole = explode(' ', trim($request->cliente));

            $query->whereHas('commessa.cliente', function ($q) use ($parole) {
                foreach ($parole as $parola) {
                    $q->where(function ($sub) use ($parola) {
                        $sub->where('nome', 'like', $parola.'%')
                            ->orWhere('cognome', 'like', $parola.'%');
                    });
                }
            });
        }

        $preventivi = $query->get();

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
            'descrizione' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $prossimoNumero = Preventivo::max('id') + 1;
        $numeroAutomatico = 'PREV-' . date('Y') . '-' . str_pad($prossimoNumero, 4, '0', STR_PAD_LEFT);

        Preventivo::create([
            'commessa_id' => $request->commessa_id,
            'numero' => $numeroAutomatico,
            'descrizione' => $request->descrizione,
            'versione' => 1,
            'stato' => 'bozza',
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
        $preventivo = Preventivo::with(
            'commessa.cliente',
            'righeProdotti.fornitore',
            'righeProdotti.servizi'
        )->findOrFail($id);

        $fornitori = Fornitore::all();
        $prodottiFornitore = ProdottoFornitore::with('fornitore')->get();

        return view('preventivi.show', compact('preventivo', 'fornitori', 'prodottiFornitore'));
    }

    public function destroy($id)
    {
        $preventivo = Preventivo::with('righeProdotti.servizi')->findOrFail($id);

        foreach ($preventivo->righeProdotti as $riga) {
            $riga->servizi()->delete();
            $riga->delete();
        }

        $preventivo->delete();

        return redirect('/preventivi')->with('success', 'Preventivo eliminato');
    }

    public function aggiungiRigaProdotto(Request $request, $id)
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

        $preventivo = Preventivo::findOrFail($id);

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

        $scontoClientePercentuale = 0;
        if ($prezzoListino > 0) {
            $scontoClientePercentuale = (($prezzoListino - $prezzoClienteUnitario) / $prezzoListino) * 100;
        }

        RigaPreventivoProdotto::create([
            'preventivo_id' => $preventivo->id,
            'fornitore_id' => $request->fornitore_id,
            'descrizione' => $request->descrizione,
            'modalita_calcolo' => $modalita,
            'quantita' => $quantita,
            'prezzo_listino' => $prezzoListino,
            'costo_netto' => $costoNetto,
            'sconto_fornitore_1' => $s1,
            'sconto_fornitore_2' => $s2,
            'sconto_fornitore_3' => $s3,
            'ricarico_percentuale' => $ricarico,
            'bene_significativo' => $request->has('bene_significativo') ? 1 : 0,
            'prezzo_cliente_unitario' => $prezzoClienteUnitario,
            'sconto_cliente_percentuale' => $scontoClientePercentuale,
            'totale_listino' => $prezzoListino * $quantita,
            'totale_costo' => $costoNetto * $quantita,
            'totale_cliente' => $prezzoClienteUnitario * $quantita,
            'ordine_visualizzazione' => 0,
            'note' => $request->note,
        ]);

        $this->aggiornaTotaliPreventivo($preventivo->id);

        return redirect('/preventivi/' . $preventivo->id);
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