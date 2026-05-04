<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Ordine;
use App\Models\RigaOrdine;
use App\Models\Preventivo;
use App\Models\Impostazione;

class OrdineController extends Controller
{
    public function index()
    {
        $ordini = Ordine::with('preventivo.commessa.cliente')
            ->where('stato', '!=', 'completo')
            ->get();

        return view('ordini.index', compact('ordini'));
    }

    public function completi()
    {
        $ordini = Ordine::with('preventivo.commessa.cliente')
            ->where('stato', 'completo')
            ->get();

        return view('ordini.completi', compact('ordini'));
    }

    public function show($id)
    {
        $ordine = Ordine::with('preventivo.commessa.cliente', 'righe.fornitore')->findOrFail($id);

        return view('ordini.show', compact('ordine'));
    }

    public function creaDaPreventivo($preventivoId)
    {
        $preventivo = Preventivo::with('commessa.cliente', 'righeProdotti.servizi')->findOrFail($preventivoId);

        if ($preventivo->ordine) {
            return redirect('/ordini/' . $preventivo->ordine->id);
        }

        $impostazioni = Impostazione::first();

        if (!$impostazioni) {
            $impostazioni = Impostazione::create([
                'iva_ordini' => 22,
            ]);
        }

        $prossimoNumero = Ordine::max('id') + 1;
        $numeroOrdine = 'ORD-' . date('Y') . '-' . str_pad($prossimoNumero, 4, '0', STR_PAD_LEFT);

        $imponibile = 0;

        foreach ($preventivo->righeProdotti as $riga) {
            $quantita = (float) ($riga->quantita ?? 1);
            $totaleServiziCostoRiga = $riga->servizi->sum('costo_brc') * $quantita;
            $imponibileRiga = $riga->totale_costo + $totaleServiziCostoRiga;
            $imponibile += $imponibileRiga;
        }

        $ivaPercentuale = $impostazioni->iva_ordini;
        $ivaImporto = $imponibile * ($ivaPercentuale / 100);
        $totaleConIva = $imponibile + $ivaImporto;

        $ordine = Ordine::create([
            'preventivo_id' => $preventivo->id,
            'commessa_id' => $preventivo->commessa_id,
            'numero' => $numeroOrdine,
            'imponibile' => $imponibile,
            'iva_percentuale' => $ivaPercentuale,
            'iva_importo' => $ivaImporto,
            'totale_con_iva' => $totaleConIva,
            'stato' => 'in_lavorazione',
        ]);

        foreach ($preventivo->righeProdotti as $riga) {
            $quantita = (float) ($riga->quantita ?? 1);
            $totaleServiziCostoRiga = $riga->servizi->sum('costo_brc') * $quantita;
            $imponibileRiga = $riga->totale_costo + $totaleServiziCostoRiga;

            RigaOrdine::create([
                'ordine_id' => $ordine->id,
                'riga_preventivo_prodotto_id' => $riga->id,
                'fornitore_id' => $riga->fornitore_id,
                'descrizione' => $riga->descrizione,
                'quantita' => $riga->quantita,
                'imponibile' => $imponibileRiga,
                'inviato' => false,
                'co_ricevuta' => false,
                'in_produzione' => false,
            ]);
        }

        return redirect('/ordini/' . $ordine->id);
    }

    public function aggiornaRiga(Request $request, $id)
    {
        $riga = RigaOrdine::findOrFail($id);

        $riga->inviato = $request->has('inviato') ? 1 : 0;
        $riga->co_ricevuta = $request->has('co_ricevuta') ? 1 : 0;
        $riga->in_produzione = $request->has('in_produzione') ? 1 : 0;

        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('ordini_pdf', 'public');
            $riga->pdf_path = $path;
        }

        $riga->save();

        $ordine = Ordine::with('righe')->findOrFail($riga->ordine_id);

        $tutteComplete = true;

        foreach ($ordine->righe as $r) {
            if (!$r->inviato || !$r->co_ricevuta || !$r->in_produzione) {
                $tutteComplete = false;
                break;
            }
        }

        if ($tutteComplete) {
            $ordine->stato = 'completo';
        } else {
            $ordine->stato = 'in_lavorazione';
        }

        $ordine->save();

        return redirect('/ordini/' . $ordine->id);
    }

    public function destroy($id)
    {
        $ordine = Ordine::with('righe')->findOrFail($id);

        foreach ($ordine->righe as $riga) {
            if ($riga->pdf_path && Storage::disk('public')->exists($riga->pdf_path)) {
                Storage::disk('public')->delete($riga->pdf_path);
            }
        }

        $ordine->righe()->delete();
        $ordine->delete();

        return redirect('/ordini')->with('success', 'Ordine eliminato');
    }
}