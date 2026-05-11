<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commessa;
use App\Models\Cliente;
use App\Models\Detrazione;
use App\Models\TipoIntervento;

class CommessaController extends Controller
{
    public function index(Request $request)
    {
        $query = Commessa::with('cliente', 'tipoIntervento', 'preventivi');

        if ($request->filled('q')) {
            $parole = explode(' ', trim($request->q));

            $query->where(function ($query) use ($parole) {
                foreach ($parole as $parola) {
                    $query->where(function ($sub) use ($parola) {
                        $sub->where('indirizzo_lavoro', 'like', '%' . $parola . '%')
                            ->orWhere('citta_lavoro', 'like', '%' . $parola . '%')
                            ->orWhere('tipo_detrazione', 'like', '%' . $parola . '%')
                            ->orWhere('tipo_lavoro', 'like', '%' . $parola . '%')
                            ->orWhereHas('tipoIntervento', function ($tipoQuery) use ($parola) {
                                $tipoQuery->where('nome', 'like', '%' . $parola . '%');
                            })
                            ->orWhereHas('cliente', function ($clienteQuery) use ($parola) {
                                $clienteQuery->where('nome', 'like', $parola . '%')
                                    ->orWhere('cognome', 'like', $parola . '%');
                            });
                    });
                }
            });
        }

        $commesse = $query->orderBy('id', 'desc')->get();

        return view('commesse.index', compact('commesse'));
    }
        public function show($id)
    {
        $commessa = Commessa::with(
        'cliente',
        'preventivi'
         )->findOrFail($id);

        return view('commesse.show', compact('commessa'));
    }


    public function create()
    {
        $clienti = Cliente::all();

        $detrazioni = Detrazione::where('attiva', 1)
            ->orderBy('nome')
            ->get();

        $tipiIntervento = TipoIntervento::where('attivo', 1)
            ->orderBy('nome')
            ->get();

        return view('commesse.create', compact('clienti', 'detrazioni', 'tipiIntervento'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clienti,id',
            'titolo' => 'nullable|string|max:255',

            'indirizzo_lavoro' => 'nullable|string|max:255',
            'citta_lavoro' => 'nullable|string|max:255',
            'provincia_lavoro' => 'nullable|string|max:255',
            'cap_lavoro' => 'nullable|string|max:10',

            'piano_posa' => 'nullable|integer|min:0',
            'autoscala' => 'nullable|boolean',

            'tipologia_abitazione' => 'nullable|string|max:255',
            'tipo_intervento_id' => 'nullable|exists:tipi_intervento,id',
            'tipo_detrazione' => 'nullable|string|max:255',
            'percentuale_detrazione' => 'nullable|numeric|min:0|max:100',

            'dati_catastali' => 'nullable|string',
            'numero_catastale' => 'nullable|string|max:255',
            'foglio_catastale' => 'nullable|string|max:255',
            'mappale_catastale' => 'nullable|string|max:255',
            'particella_catastale' => 'nullable|string|max:255',
            'sub_catastale' => 'nullable|string|max:255',

            'pratica_edilizia_tipo' => 'nullable|string|max:255',
            'pratica_edilizia_numero' => 'nullable|string|max:255',
            'pratica_edilizia_protocollo' => 'nullable|string|max:255',

            'pratica_enea' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        $tipoIntervento = null;

        if ($request->tipo_intervento_id) {
            $tipoIntervento = TipoIntervento::find($request->tipo_intervento_id);
        }

        $percentualeDetrazione = $this->calcolaPercentualeDetrazione($request->tipo_detrazione);

        Commessa::create([
            'cliente_id' => $request->cliente_id,
            'titolo' => $request->titolo,

            'indirizzo_lavoro' => $request->indirizzo_lavoro,
            'citta_lavoro' => $request->citta_lavoro,
            'provincia_lavoro' => $request->provincia_lavoro,
            'cap_lavoro' => $request->cap_lavoro,

            'piano_posa' => $request->piano_posa,
            'autoscala' => $request->has('autoscala') ? 1 : 0,

            'tipologia_abitazione' => $request->tipologia_abitazione,
            'tipo_intervento_id' => $request->tipo_intervento_id,
            'tipo_lavoro' => $tipoIntervento ? $tipoIntervento->nome : null,

            'tipo_detrazione' => $request->tipo_detrazione,
            'percentuale_detrazione' => $percentualeDetrazione,

            'dati_catastali' => $request->dati_catastali,
            'numero_catastale' => $request->numero_catastale,
            'foglio_catastale' => $request->foglio_catastale,
            'mappale_catastale' => $request->mappale_catastale,
            'particella_catastale' => $request->particella_catastale,
            'sub_catastale' => $request->sub_catastale,

            'pratica_edilizia_tipo' => $request->pratica_edilizia_tipo,
            'pratica_edilizia_numero' => $request->pratica_edilizia_numero,
            'pratica_edilizia_protocollo' => $request->pratica_edilizia_protocollo,

            'pratica_enea' => $request->has('pratica_enea') ? 1 : 0,

            'stato' => 'aperta',
            'note' => $request->note,
        ]);

        return redirect('/commesse')->with('success', 'Commessa creata correttamente');
    }

    public function edit($id)
    {
        $commessa = Commessa::findOrFail($id);
        $clienti = Cliente::all();

        $detrazioni = Detrazione::where('attiva', 1)
            ->orderBy('nome')
            ->get();

        $tipiIntervento = TipoIntervento::where('attivo', 1)
            ->orderBy('nome')
            ->get();

        return view('commesse.edit', compact('commessa', 'clienti', 'detrazioni', 'tipiIntervento'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clienti,id',
            'titolo' => 'nullable|string|max:255',

            'indirizzo_lavoro' => 'nullable|string|max:255',
            'citta_lavoro' => 'nullable|string|max:255',
            'provincia_lavoro' => 'nullable|string|max:255',
            'cap_lavoro' => 'nullable|string|max:10',

            'piano_posa' => 'nullable|integer|min:0',
            'autoscala' => 'nullable|boolean',

            'tipologia_abitazione' => 'nullable|string|max:255',
            'tipo_intervento_id' => 'nullable|exists:tipi_intervento,id',
            'tipo_detrazione' => 'nullable|string|max:255',
            'percentuale_detrazione' => 'nullable|numeric|min:0|max:100',

            'dati_catastali' => 'nullable|string',
            'numero_catastale' => 'nullable|string|max:255',
            'foglio_catastale' => 'nullable|string|max:255',
            'mappale_catastale' => 'nullable|string|max:255',
            'particella_catastale' => 'nullable|string|max:255',
            'sub_catastale' => 'nullable|string|max:255',

            'pratica_edilizia_tipo' => 'nullable|string|max:255',
            'pratica_edilizia_numero' => 'nullable|string|max:255',
            'pratica_edilizia_protocollo' => 'nullable|string|max:255',

            'pratica_enea' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        $commessa = Commessa::findOrFail($id);

        $tipoIntervento = null;

        if ($request->tipo_intervento_id) {
            $tipoIntervento = TipoIntervento::find($request->tipo_intervento_id);
        }

        $percentualeDetrazione = $this->calcolaPercentualeDetrazione($request->tipo_detrazione);

        $commessa->update([
            'cliente_id' => $request->cliente_id,
            'titolo' => $request->titolo,

            'indirizzo_lavoro' => $request->indirizzo_lavoro,
            'citta_lavoro' => $request->citta_lavoro,
            'provincia_lavoro' => $request->provincia_lavoro,
            'cap_lavoro' => $request->cap_lavoro,

            'piano_posa' => $request->piano_posa,
            'autoscala' => $request->has('autoscala') ? 1 : 0,

            'tipologia_abitazione' => $request->tipologia_abitazione,
            'tipo_intervento_id' => $request->tipo_intervento_id,
            'tipo_lavoro' => $tipoIntervento ? $tipoIntervento->nome : null,

            'tipo_detrazione' => $request->tipo_detrazione,
            'percentuale_detrazione' => $percentualeDetrazione,

            'dati_catastali' => $request->dati_catastali,
            'numero_catastale' => $request->numero_catastale,
            'foglio_catastale' => $request->foglio_catastale,
            'mappale_catastale' => $request->mappale_catastale,
            'particella_catastale' => $request->particella_catastale,
            'sub_catastale' => $request->sub_catastale,

            'pratica_edilizia_tipo' => $request->pratica_edilizia_tipo,
            'pratica_edilizia_numero' => $request->pratica_edilizia_numero,
            'pratica_edilizia_protocollo' => $request->pratica_edilizia_protocollo,

            'pratica_enea' => $request->has('pratica_enea') ? 1 : 0,

            'note' => $request->note,
        ]);

        return redirect('/commesse')->with('success', 'Commessa aggiornata correttamente');
    }

    public function destroy($id)
    {
        try {
            $commessa = Commessa::findOrFail($id);
            $commessa->delete();

            return redirect('/commesse')
                ->with('success', 'Commessa eliminata');

        } catch (\Exception $e) {
            return redirect('/commesse')
                ->with('error', 'Impossibile eliminare la commessa perché collegata a preventivi o ordini');
        }
    }

    private function calcolaPercentualeDetrazione($nomeDetrazione)
    {
        if (!$nomeDetrazione) {
            return null;
        }

        $detrazione = Detrazione::where('nome', $nomeDetrazione)->first();

        if (!$detrazione) {
            return null;
        }

        return $detrazione->percentuale;
    }

    
}