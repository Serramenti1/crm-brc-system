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
        $ordini = Ordine::with('commessa.cliente', 'righe')
            ->where('stato', 'in_lavorazione')
            ->get();

        return view('ordini.index', compact('ordini'));
    }

    public function completi()
    {
        $ordini = Ordine::with('commessa.cliente', 'righe')
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

        $ordini = Ordine::with('commessa.cliente', 'righe')
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
            'ultimo_avanzamento_tipo' => null,
            'ultimo_avanzamento_riga_id' => null,
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

        $ultimoTipo = null;
        $ultimoRigaId = null;

        if ($ordine->stato == 'in_lavorazione') {
            $primaInProduzione = $riga->in_produzione;

            $riga->inviato = $request->has('inviato') ? 1 : 0;
            $riga->co_ricevuta = $request->has('co_ricevuta') ? 1 : 0;
            $riga->in_produzione = $request->has('in_produzione') ? 1 : 0;

            if (!$primaInProduzione && $riga->in_produzione) {
                $ultimoTipo = 'in_produzione';
                $ultimoRigaId = $riga->id;
            }
        }

        if ($ordine->stato == 'completo_attesa_merce') {
            $primaMerceArrivata = $riga->merce_arrivata;

            $riga->merce_arrivata = $request->has('merce_arrivata') ? 1 : 0;

            if (!$primaMerceArrivata && $riga->merce_arrivata) {
                $ultimoTipo = 'merce_arrivata';
                $ultimoRigaId = $riga->id;
            }
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

        $messaggioCambioStato = $this->aggiornaStatoOrdine($ordine, $ultimoTipo, $ultimoRigaId);

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

        $ultimoTipo = null;
        $ultimoRigaId = null;

        if ($ordine->stato == 'attesa_saldo_merce') {
            $primaSaldo = $ordine->saldo_merce_ricevuto;

            $ordine->saldo_merce_ricevuto = $request->has('saldo_merce_ricevuto') ? 1 : 0;

            if (!$primaSaldo && $ordine->saldo_merce_ricevuto) {
                $ultimoTipo = 'saldo_merce_ricevuto';
            }
        }

        if ($ordine->stato == 'programmare_posa') {
            $primaFattura = $ordine->fattura_saldo_posa_fatta;

            $ordine->posa_effettuata = $request->has('posa_effettuata') ? 1 : 0;
            $ordine->fattura_saldo_posa_fatta = $request->has('fattura_saldo_posa_fatta') ? 1 : 0;

            if (!$primaFattura && $ordine->fattura_saldo_posa_fatta) {
                $ultimoTipo = 'fattura_saldo_posa_fatta';
            }
        }

        $ordine->save();

        $ordine = Ordine::with('righe')->findOrFail($ordine->id);

        $messaggioCambioStato = $this->aggiornaStatoOrdine($ordine, $ultimoTipo, $ultimoRigaId);

        if ($messaggioCambioStato) {
            return redirect('/ordini/' . $ordine->id)
                ->with('success', $messaggioCambioStato);
        }

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Stato ordine aggiornato correttamente.');
    }

    private function aggiornaStatoOrdine($ordine, $ultimoTipo = null, $ultimoRigaId = null)
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
                $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                $ordine->ultimo_avanzamento_riga_id = $ultimoRigaId;
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
                $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                $ordine->ultimo_avanzamento_riga_id = $ultimoRigaId;
                $messaggio = 'Tutta la merce è arrivata. L’ordine è stato spostato in: Attesa saldo merce.';
            }
        }

        if ($ordine->stato == 'attesa_saldo_merce') {
            if ($ordine->saldo_merce_ricevuto) {
                $ordine->stato = 'programmare_posa';
                $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                $ordine->ultimo_avanzamento_riga_id = null;
                $messaggio = 'Saldo merce ricevuto. L’ordine è stato spostato in: Programmare posa.';
            }
        }

        if ($ordine->stato == 'programmare_posa') {
            if ($ordine->posa_effettuata && $ordine->fattura_saldo_posa_fatta) {
                $ordine->stato = 'concluso';
                $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                $ordine->ultimo_avanzamento_riga_id = null;
                $messaggio = 'Posa effettuata e fattura saldo posa fatta. L’ordine è stato concluso.';
            }
        }

        $ordine->save();

        return $messaggio;
    }

    public function tornaStatoPrecedente($id)
    {
        $ordine = Ordine::with('righe')->findOrFail($id);

        if ($ordine->stato == 'completo_attesa_merce') {
            if ($ordine->ultimo_avanzamento_tipo == 'in_produzione' && $ordine->ultimo_avanzamento_riga_id) {
                $riga = RigaOrdine::find($ordine->ultimo_avanzamento_riga_id);

                if ($riga) {
                    $riga->in_produzione = 0;
                    $riga->save();
                }
            }

            $ordine->stato = 'in_lavorazione';
            $messaggio = 'Ordine riportato in lavorazione. Rimossa solo la spunta che aveva causato l’avanzamento.';

        } elseif ($ordine->stato == 'attesa_saldo_merce') {
            if ($ordine->ultimo_avanzamento_tipo == 'merce_arrivata' && $ordine->ultimo_avanzamento_riga_id) {
                $riga = RigaOrdine::find($ordine->ultimo_avanzamento_riga_id);

                if ($riga) {
                    $riga->merce_arrivata = 0;
                    $riga->save();
                }
            }

            $ordine->stato = 'completo_attesa_merce';
            $messaggio = 'Ordine riportato in attesa merce. Rimossa solo la spunta che aveva causato l’avanzamento.';

        } elseif ($ordine->stato == 'programmare_posa') {
            if ($ordine->ultimo_avanzamento_tipo == 'saldo_merce_ricevuto') {
                $ordine->saldo_merce_ricevuto = 0;
            }

            $ordine->stato = 'attesa_saldo_merce';
            $messaggio = 'Ordine riportato in attesa saldo merce. Rimossa la spunta saldo merce ricevuto.';

        } elseif ($ordine->stato == 'concluso') {
            if ($ordine->ultimo_avanzamento_tipo == 'fattura_saldo_posa_fatta') {
                $ordine->fattura_saldo_posa_fatta = 0;
            }

            $ordine->stato = 'programmare_posa';
            $messaggio = 'Ordine riportato in programmare posa. Rimossa la spunta fattura saldo posa fatta.';

        } else {
            return redirect('/ordini/' . $ordine->id)
                ->with('error', 'Questo ordine non può tornare a uno stato precedente.');
        }

        $ordine->ultimo_avanzamento_tipo = null;
        $ordine->ultimo_avanzamento_riga_id = null;
        $ordine->save();

        return redirect('/ordini/' . $ordine->id)
            ->with('success', $messaggio);
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