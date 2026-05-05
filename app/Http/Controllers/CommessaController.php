<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commessa;
use App\Models\Cliente;
use App\Models\Detrazione;

class CommessaController extends Controller
{
    public function index()
    {
        $commesse = Commessa::with('cliente')->get();

        return view('commesse.index', compact('commesse'));
    }

    public function create()
    {
        $clienti = Cliente::all();

        $detrazioni = Detrazione::where('attiva', 1)
            ->orderBy('nome')
            ->get();

        return view('commesse.create', compact('clienti', 'detrazioni'));
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
            'tipo_lavoro' => 'nullable|string|max:255',
            'tipo_detrazione' => 'nullable|string|max:255',
            'percentuale_detrazione' => 'nullable|numeric|min:0|max:100',

            'dati_catastali' => 'nullable|string',
            'numero_catastale' => 'nullable|string|max:255',

            'pratica_edilizia_tipo' => 'nullable|string|max:255',
            'pratica_edilizia_numero' => 'nullable|string|max:255',
            'pratica_edilizia_protocollo' => 'nullable|string|max:255',

            'pratica_enea' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

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
            'tipo_lavoro' => $request->tipo_lavoro,
            'tipo_detrazione' => $request->tipo_detrazione,
            'percentuale_detrazione' => $percentualeDetrazione,

            'dati_catastali' => $request->dati_catastali,
            'numero_catastale' => $request->numero_catastale,

            'pratica_edilizia_tipo' => $request->pratica_edilizia_tipo,
            'pratica_edilizia_numero' => $request->pratica_edilizia_numero,
            'pratica_edilizia_protocollo' => $request->pratica_edilizia_protocollo,

            'pratica_enea' => $request->has('pratica_enea') ? 1 : 0,

            'stato' => 'aperta',
            'note' => $request->note,
        ]);

        return redirect('/commesse');
    }

    public function edit($id)
    {
        $commessa = Commessa::findOrFail($id);
        $clienti = Cliente::all();

        $detrazioni = Detrazione::where('attiva', 1)
            ->orderBy('nome')
            ->get();

        return view('commesse.edit', compact('commessa', 'clienti', 'detrazioni'));
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
            'tipo_lavoro' => 'nullable|string|max:255',
            'tipo_detrazione' => 'nullable|string|max:255',
            'percentuale_detrazione' => 'nullable|numeric|min:0|max:100',

            'dati_catastali' => 'nullable|string',
            'numero_catastale' => 'nullable|string|max:255',

            'pratica_edilizia_tipo' => 'nullable|string|max:255',
            'pratica_edilizia_numero' => 'nullable|string|max:255',
            'pratica_edilizia_protocollo' => 'nullable|string|max:255',

            'pratica_enea' => 'nullable|boolean',
            'note' => 'nullable|string',
        ]);

        $commessa = Commessa::findOrFail($id);

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
            'tipo_lavoro' => $request->tipo_lavoro,
            'tipo_detrazione' => $request->tipo_detrazione,
            'percentuale_detrazione' => $percentualeDetrazione,

            'dati_catastali' => $request->dati_catastali,
            'numero_catastale' => $request->numero_catastale,

            'pratica_edilizia_tipo' => $request->pratica_edilizia_tipo,
            'pratica_edilizia_numero' => $request->pratica_edilizia_numero,
            'pratica_edilizia_protocollo' => $request->pratica_edilizia_protocollo,

            'pratica_enea' => $request->has('pratica_enea') ? 1 : 0,

            'note' => $request->note,
        ]);

        return redirect('/commesse');
    }

    public function destroy($id)
    {
        $commessa = Commessa::findOrFail($id);
        $commessa->delete();

        return redirect('/commesse');
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