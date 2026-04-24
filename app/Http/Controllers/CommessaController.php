<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commessa;
use App\Models\Cliente;

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
        return view('commesse.create', compact('clienti'));
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
            'tipologia_abitazione' => 'nullable|string|max:255',
            'tipo_lavoro' => 'nullable|string|max:255',
            'tipo_detrazione' => 'nullable|string|max:255',
            'percentuale_detrazione' => 'nullable|numeric|min:0|max:100',
            'stato' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        Commessa::create([
            'cliente_id' => $request->cliente_id,
            'titolo' => $request->titolo,
            'indirizzo_lavoro' => $request->indirizzo_lavoro,
            'citta_lavoro' => $request->citta_lavoro,
            'provincia_lavoro' => $request->provincia_lavoro,
            'cap_lavoro' => $request->cap_lavoro,
            'tipologia_abitazione' => $request->tipologia_abitazione,
            'tipo_lavoro' => $request->tipo_lavoro,
            'tipo_detrazione' => $request->tipo_detrazione,
            'percentuale_detrazione' => $request->percentuale_detrazione,
            'stato' => $request->stato,
            'note' => $request->note,
        ]);

        return redirect('/commesse');
    }

    public function edit($id)
    {
        $commessa = Commessa::findOrFail($id);
        $clienti = Cliente::all();

        return view('commesse.edit', compact('commessa', 'clienti'));
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
            'tipologia_abitazione' => 'nullable|string|max:255',
            'tipo_lavoro' => 'nullable|string|max:255',
            'tipo_detrazione' => 'nullable|string|max:255',
            'percentuale_detrazione' => 'nullable|numeric|min:0|max:100',
            'stato' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $commessa = Commessa::findOrFail($id);

        $commessa->update([
            'cliente_id' => $request->cliente_id,
            'titolo' => $request->titolo,
            'indirizzo_lavoro' => $request->indirizzo_lavoro,
            'citta_lavoro' => $request->citta_lavoro,
            'provincia_lavoro' => $request->provincia_lavoro,
            'cap_lavoro' => $request->cap_lavoro,
            'tipologia_abitazione' => $request->tipologia_abitazione,
            'tipo_lavoro' => $request->tipo_lavoro,
            'tipo_detrazione' => $request->tipo_detrazione,
            'percentuale_detrazione' => $request->percentuale_detrazione,
            'stato' => $request->stato,
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
}