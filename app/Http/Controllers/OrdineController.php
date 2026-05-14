<?php

namespace App\Http\Controllers;

use App\Services\CalcoloIvaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Ordine;
use App\Models\RigaOrdine;
use App\Models\Preventivo;
use App\Models\Fornitore;
use App\Models\ProdottoFornitore;
use App\Models\Impostazione;

class OrdineController extends Controller
{
    public function index()
    {
        $ordini = Ordine::with('commessa.cliente','commessa.tipoIntervento', 'righe')
            ->where('stato', 'preparazione_contratto')
            ->get();

        return view('ordini.index', compact('ordini'));
    }

    public function completi()
    {
        $ordini = Ordine::with('commessa.cliente','commessa.tipoIntervento', 'righe')
            ->where('stato', 'concluso')
            ->get();

        return view('ordini.completi', compact('ordini'));
    }

    public function perStato($stato)
    {
        $statiValidi = [
            'preparazione_contratto',
            'in_lavorazione',
            'completo_attesa_merce',
            'attesa_saldo_merce',
            'programmare_posa',
            'concluso',
            'archiviato',
        ];

        if (!in_array($stato, $statiValidi)) {
            abort(404);
        }

        $ordini = Ordine::with('commessa.cliente','commessa.tipoIntervento', 'righe')
            ->where('stato', $stato)
            ->get();

        return view('ordini.index', compact('ordini', 'stato'));
    }

    public function show($id)
{
    $ordine = Ordine::with(
        'commessa.cliente',
        'commessa.tipoIntervento',
        'preventivo',
        'righe.fornitore',
        'righe.servizi'
    )->findOrFail($id);

    $fornitori = Fornitore::all();

    $prodottiFornitore = ProdottoFornitore::with('fornitore')->get();

    $impostazioni = Impostazione::first();

    return view('ordini.show', compact(
        'ordine',
        'fornitori',
        'prodottiFornitore',
        'impostazioni'
    ));
}

    public function creaDaPreventivo($preventivoId)
    {
        $preventivo = Preventivo::with(
            'commessa.cliente',
            'righeProdotti.servizi',
            'righeProdotti.fornitore'
        )->findOrFail($preventivoId);

        if ($preventivo->ordine) {
            return redirect('/ordini/' . $preventivo->ordine->id);
        }

        $calcoloIvaService = new CalcoloIvaService();
        $calcoloIva = $calcoloIvaService->calcolaDaPreventivo($preventivo);

        $prossimoNumero = Ordine::max('id') + 1;
        $numeroOrdine = 'ORD-' . date('Y') . '-' . str_pad($prossimoNumero, 4, '0', STR_PAD_LEFT);

        $ordine = Ordine::create([
            'preventivo_id' => $preventivo->id,
            'commessa_id' => $preventivo->commessa_id,
            'numero' => $numeroOrdine,

            'imponibile' => $calcoloIva['totale_cliente'],
            'imponibile_4' => $calcoloIva['imponibile_4'],
            'imponibile_10' => $calcoloIva['imponibile_10'],
            'imponibile_22' => $calcoloIva['imponibile_22'],

            'iva_percentuale' => 0,
            'iva_importo' => $calcoloIva['totale_iva'],
            'iva_4' => $calcoloIva['iva_4'],
            'iva_10' => $calcoloIva['iva_10'],
            'iva_22' => $calcoloIva['iva_22'],
            'totale_iva' => $calcoloIva['totale_iva'],
            'totale_con_iva' => $calcoloIva['totale_con_iva'],

            'stato' => 'preparazione_contratto',

            'rilievo_effettuato' => false,
            'contratto_firmato' => false,
            'acconto_versato' => false,

            'saldo_merce_ricevuto' => false,
            'posa_effettuata' => false,
            'fattura_saldo_posa_fatta' => false,

            'saldo_finale_ricevuto' => false,
            'invio_enea_effettuato' => false,

            'ultimo_avanzamento_tipo' => null,
            'ultimo_avanzamento_riga_id' => null,
        ]);

        foreach ($preventivo->righeProdotti as $riga) {
            $quantita = (float) ($riga->quantita ?? 1);

            $totaleServiziRiga = 0;

            foreach ($riga->servizi as $servizio) {
                $totaleServiziRiga += (float) $servizio->prezzo_cliente * $quantita;
            }

            $imponibileRiga = (float) $riga->totale_cliente + $totaleServiziRiga;

            $nuovaRigaOrdine = RigaOrdine::create([
    'ordine_id' => $ordine->id,
    'riga_preventivo_prodotto_id' => $riga->id,
    'fornitore_id' => $riga->fornitore_id,
    'descrizione' => $riga->descrizione,
    'quantita' => $riga->quantita,
    'imponibile' => $imponibileRiga,
    'modalita_calcolo' => $riga->modalita_calcolo,
'prezzo_listino' => $riga->prezzo_listino,
'costo_netto' => $riga->costo_netto,
'sconto_fornitore_1' => $riga->sconto_fornitore_1,
'sconto_fornitore_2' => $riga->sconto_fornitore_2,
'sconto_fornitore_3' => $riga->sconto_fornitore_3,
'ricarico_percentuale' => $riga->ricarico_percentuale,
'bene_significativo' => $riga->bene_significativo,
'prezzo_cliente_unitario' => $riga->prezzo_cliente_unitario,
'totale_cliente' => $riga->totale_cliente,
'totale_costo' => $riga->totale_costo,
'note' => $riga->note,
    'inviato' => false,
    'co_ricevuta' => false,
    'in_produzione' => false,
    'merce_arrivata' => false,
    'pdf_path' => null,
]);

foreach ($riga->servizi as $servizio) {

    \App\Models\RigaOrdineServizio::create([
        'riga_ordine_id' => $nuovaRigaOrdine->id,
        'tipo_servizio' => $servizio->tipo_servizio,
        'descrizione' => $servizio->descrizione,
        'costo_brc' => $servizio->costo_brc,
        'ricarico_percentuale' => $servizio->ricarico_percentuale,
        'prezzo_cliente' => $servizio->prezzo_cliente,
        'note' => $servizio->note,
    ]);

}
        }

        return redirect('/ordini/' . $ordine->id)
            ->with('success', 'Ordine creato correttamente. Stato: Preparazione contratto.');
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

        $ordine = Ordine::with('righe', 'commessa')->findOrFail($ordine->id);

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
        $ordine = Ordine::with('righe', 'commessa')->findOrFail($id);

        $ultimoTipo = null;
        $ultimoRigaId = null;

        if ($ordine->stato == 'preparazione_contratto') {
            $primaRilievo = $ordine->rilievo_effettuato;
            $primaContratto = $ordine->contratto_firmato;
            $primaAcconto = $ordine->acconto_versato;

            $ordine->rilievo_effettuato = $request->has('rilievo_effettuato') ? 1 : 0;
            $ordine->contratto_firmato = $request->has('contratto_firmato') ? 1 : 0;
            $ordine->acconto_versato = $request->has('acconto_versato') ? 1 : 0;

            if (!$primaRilievo && $ordine->rilievo_effettuato) {
                $ultimoTipo = 'rilievo_effettuato';
            }

            if (!$primaContratto && $ordine->contratto_firmato) {
                $ultimoTipo = 'contratto_firmato';
            }

            if (!$primaAcconto && $ordine->acconto_versato) {
                $ultimoTipo = 'acconto_versato';
            }
        }

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

        if ($ordine->stato == 'concluso') {
            $primaSaldoFinale = $ordine->saldo_finale_ricevuto;
            $primaEnea = $ordine->invio_enea_effettuato;

            $ordine->saldo_finale_ricevuto = $request->has('saldo_finale_ricevuto') ? 1 : 0;

            if ($ordine->commessa && $ordine->commessa->pratica_enea) {
                $ordine->invio_enea_effettuato = $request->has('invio_enea_effettuato') ? 1 : 0;
            } else {
                $ordine->invio_enea_effettuato = 0;
            }

            if (!$primaSaldoFinale && $ordine->saldo_finale_ricevuto) {
                $ultimoTipo = 'saldo_finale_ricevuto';
            }

            if (!$primaEnea && $ordine->invio_enea_effettuato) {
                $ultimoTipo = 'invio_enea_effettuato';
            }
        }

        $ordine->save();

        $ordine = Ordine::with('righe', 'commessa')->findOrFail($ordine->id);

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
        if ($ordine->stato == 'preparazione_contratto') {
            if (
                $ordine->rilievo_effettuato &&
                $ordine->contratto_firmato &&
                $ordine->acconto_versato
            ) {
                $ordine->stato = 'in_lavorazione';
                $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                $ordine->ultimo_avanzamento_riga_id = null;
                $ordine->save();

                return 'Rilievo effettuato, contratto firmato e acconto versato. L’ordine è stato spostato in: In lavorazione.';
            }

            return null;
        }

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
                $ordine->save();

                return 'Tutte le righe sono complete. L’ordine è stato spostato in: Completo - attesa merce.';
            }

            return null;
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
                $ordine->save();

                return 'Tutta la merce è arrivata. L’ordine è stato spostato in: Attesa saldo merce.';
            }

            return null;
        }

        if ($ordine->stato == 'attesa_saldo_merce') {
            if ($ordine->saldo_merce_ricevuto) {
                $ordine->stato = 'programmare_posa';
                $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                $ordine->ultimo_avanzamento_riga_id = null;
                $ordine->save();

                return 'Saldo merce ricevuto. L’ordine è stato spostato in: Programmare posa.';
            }

            return null;
        }

        if ($ordine->stato == 'programmare_posa') {
            if ($ordine->posa_effettuata && $ordine->fattura_saldo_posa_fatta) {
                $ordine->stato = 'concluso';
                $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                $ordine->ultimo_avanzamento_riga_id = null;
                $ordine->save();

                return 'Posa effettuata e fattura saldo posa fatta. L’ordine è stato concluso.';
            }

            return null;
        }

        if ($ordine->stato == 'concluso') {
            $serveEnea = $ordine->commessa && $ordine->commessa->pratica_enea;

            if ($serveEnea) {
                if ($ordine->saldo_finale_ricevuto && $ordine->invio_enea_effettuato) {
                    $ordine->stato = 'archiviato';
                    $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                    $ordine->ultimo_avanzamento_riga_id = null;
                    $ordine->save();

                    return 'Saldo finale ricevuto e invio ENEA effettuato. L’ordine è stato archiviato.';
                }
            } else {
                if ($ordine->saldo_finale_ricevuto) {
                    $ordine->stato = 'archiviato';
                    $ordine->ultimo_avanzamento_tipo = $ultimoTipo;
                    $ordine->ultimo_avanzamento_riga_id = null;
                    $ordine->save();

                    return 'Saldo finale ricevuto. L’ordine è stato archiviato.';
                }
            }

            return null;
        }

        return null;
    }

    public function tornaStatoPrecedente($id)
    {
        $ordine = Ordine::with('righe', 'commessa')->findOrFail($id);

        if ($ordine->stato == 'in_lavorazione') {

            if ($ordine->ultimo_avanzamento_tipo == 'rilievo_effettuato') {
                $ordine->rilievo_effettuato = 0;
            }

            if ($ordine->ultimo_avanzamento_tipo == 'contratto_firmato') {
                $ordine->contratto_firmato = 0;
            }

            if ($ordine->ultimo_avanzamento_tipo == 'acconto_versato') {
                $ordine->acconto_versato = 0;
            }

            $ordine->stato = 'preparazione_contratto';
            $messaggio = 'Ordine riportato in preparazione contratto. Rimossa la spunta che aveva causato l’avanzamento.';

        } elseif ($ordine->stato == 'completo_attesa_merce') {

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

        } elseif ($ordine->stato == 'archiviato') {

            if ($ordine->ultimo_avanzamento_tipo == 'invio_enea_effettuato') {
                $ordine->invio_enea_effettuato = 0;
            } elseif ($ordine->ultimo_avanzamento_tipo == 'saldo_finale_ricevuto') {
                $ordine->saldo_finale_ricevuto = 0;
            }

            $ordine->stato = 'concluso';
            $messaggio = 'Ordine riportato in conclusi. Rimossa la spunta che aveva causato l’archiviazione.';

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

        return redirect('/ordini/stato/preparazione_contratto')
            ->with('success', 'Ordine eliminato correttamente.');
    }
    
    public function visualizza($id)
{
    $ordine = Ordine::with(
        'commessa.cliente',
        'commessa.tipoIntervento',
        'preventivo',
        'righe.fornitore',
        'righe.servizi'
    )->findOrFail($id);

    return view('ordini.visualizza', compact('ordine'));
}
}