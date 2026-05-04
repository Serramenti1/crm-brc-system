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
        $ordini = Ordine::with('commessa.cliente')
            ->where('stato', 'in_lavorazione')
            ->get();

        return view('ordini.index', compact('ordini'));
    }

    public function completi()
    {
        $ordini = Ordine::with('commessa.cliente')
            ->where('stato', 'concluso')
            ->get();

        return view('ordini.completi', compact('ordini'));
    }

    public function perStato($stato)
    {
        $statiValidi = [
            'in_lavorazione',
            'completo_attesa_merce',
            'attesa_saldo_merce',
            'programmare_posa',
            'concluso',
        ];

        if (!in_array($stato, $statiValidi)) {
            abort(404);
        }

        $ordini = Ordine::with('commessa.cliente')
            ->where('stato', $stato)
            ->get();

        return view('ordini.index', compact('ordini', 'stato'));
    }

    public function show($id)
    {
        $ordine = Ordine::with('commessa.cliente', 'preventivo', 'righe.fornitore')->findOrFail($id);

        return view('ordini.show', compact('ordine'));
    }

    public function creaDaPreventivo($preventivoId)
    {
        $preventivo = Preventivo::with('commessa.cliente', 'righeProdotti')->findOrFail($preventivoId);

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

        /*
            ORDINE = solo costo netto prodotti a noi.
            I servizi NON vengono calcolati.
        */
        $imponibile = 0;

        foreach ($preventivo->righeProdotti as $riga) {
            $imponibile += (float) $riga->totale_costo;
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
            'saldo_merce_ricevuto' => false,
            'posa_effettuata' => false,
            'fattura_saldo_posa_fatta' => false,
        ]);

        foreach ($preventivo->righeProdotti as $riga) {
            RigaOrdine::create([
                'ordine_id' => $ordine->id,
                'riga_preventivo_prodotto_id' => $riga->id,
                'fornitore_id' => $riga->fornitore_id,
                'descrizione' => $riga->descrizione,
                'quantita' => $riga->quantita,
                'imponibile' => $riga->totale_costo,
                'inviato' => false,
                'co_ricevuta' => false,
                'in_produzione' => false,
                'merce_arrivata' => false,
                'pdf_path' => null,
            ]);
        }

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Ordine creato correttamente. Stato: In lavorazione.');
    }

    public function aggiornaRiga(Request $request, $id)
    {
        $riga = RigaOrdine::findOrFail($id);
        $ordine = Ordine::with('righe')->findOrFail($riga->ordine_id);

        if ($ordine->stato == 'in_lavorazione') {
            $riga->inviato = $request->has('inviato') ? 1 : 0;
            $riga->co_ricevuta = $request->has('co_ricevuta') ? 1 : 0;
            $riga->in_produzione = $request->has('in_produzione') ? 1 : 0;
        }

        if ($ordine->stato == 'completo_attesa_merce') {
            $riga->merce_arrivata = $request->has('merce_arrivata') ? 1 : 0;
        }

        if ($request->hasFile('pdf')) {
            if ($riga->pdf_path && Storage::disk('public')->exists($riga->pdf_path)) {
                Storage::disk('public')->delete($riga->pdf_path);
            }

            $path = $request->file('pdf')->store('ordini_pdf', 'public');
            $riga->pdf_path = $path;
        }

        $riga->save();

        $ordine = Ordine::with('righe')->findOrFail($ordine->id);

        $messaggioCambioStato = $this->aggiornaStatoOrdine($ordine);

        if ($messaggioCambioStato) {
            return redirect('/ordini/' . $ordine->id)
                ->with('success', $messaggioCambioStato);
        }

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Riga ordine aggiornata correttamente.');
    }

    public function aggiornaStatoAvanzato(Request $request, $id)
    {
        $ordine = Ordine::with('righe')->findOrFail($id);

        if ($ordine->stato == 'attesa_saldo_merce') {
            $ordine->saldo_merce_ricevuto = $request->has('saldo_merce_ricevuto') ? 1 : 0;
        }

        if ($ordine->stato == 'programmare_posa') {
            $ordine->posa_effettuata = $request->has('posa_effettuata') ? 1 : 0;
            $ordine->fattura_saldo_posa_fatta = $request->has('fattura_saldo_posa_fatta') ? 1 : 0;
        }

        $ordine->save();

        $ordine = Ordine::with('righe')->findOrFail($ordine->id);

        $messaggioCambioStato = $this->aggiornaStatoOrdine($ordine);

        if ($messaggioCambioStato) {
            return redirect('/ordini/' . $ordine->id)
                ->with('success', $messaggioCambioStato);
        }

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Stato ordine aggiornato correttamente.');
    }

    private function aggiornaStatoOrdine($ordine)
    {
        $messaggio = null;

        if ($ordine->stato == 'in_lavorazione') {
            $tuttePronte = true;

            foreach ($ordine->righe as $riga) {
                if (!$riga->inviato || !$riga->co_ricevuta || !$riga->in_produzione) {
                    $tuttePronte = false;
                    break;
                }
            }

            if ($tuttePronte) {
                $ordine->stato = 'completo_attesa_merce';
                $messaggio = 'Tutte le righe sono complete. L’ordine è stato spostato in: Completo - attesa merce.';
            }
        }

        if ($ordine->stato == 'completo_attesa_merce') {
            $merceTuttaArrivata = true;

            foreach ($ordine->righe as $riga) {
                if (!$riga->merce_arrivata) {
                    $merceTuttaArrivata = false;
                    break;
                }
            }

            if ($merceTuttaArrivata) {
                $ordine->stato = 'attesa_saldo_merce';
                $messaggio = 'Tutta la merce è arrivata. L’ordine è stato spostato in: Attesa saldo merce.';
            }
        }

        if ($ordine->stato == 'attesa_saldo_merce') {
            if ($ordine->saldo_merce_ricevuto) {
                $ordine->stato = 'programmare_posa';
                $messaggio = 'Saldo merce ricevuto. L’ordine è stato spostato in: Programmare posa.';
            }
        }

        if ($ordine->stato == 'programmare_posa') {
            if ($ordine->posa_effettuata && $ordine->fattura_saldo_posa_fatta) {
                $ordine->stato = 'concluso';
                $messaggio = 'Posa effettuata e fattura saldo posa fatta. L’ordine è stato concluso.';
            }
        }

        $ordine->save();

        return $messaggio;
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

        return redirect('/ordini/stato/in_lavorazione')
            ->with('success', 'Ordine eliminato correttamente.');
    }
}