<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdottoFornitore;
use App\Models\Fornitore;

class ProdottoFornitoreController extends Controller
{
    public function index(Request $request)
{
    $query = ProdottoFornitore::with('fornitore');

    if ($request->filled('q')) {
        $parole = explode(' ', trim($request->q));

        $query->where(function ($q) use ($parole) {
            foreach ($parole as $parola) {
                $q->where(function ($sub) use ($parola) {
                    $sub->where('descrizione', 'like', $parola.'%')
                        ->orWhereHas('fornitore', function ($f) use ($parola) {
                            $f->where('ragione_sociale', 'like', $parola.'%');
                        });
                });
            }
        });
    }

    $prodotti = $query->get();

    return view('prodotti_fornitore.index', compact('prodotti'));
}

    public function create()
    {
        $fornitori = Fornitore::all();
        return view('prodotti_fornitore.create', compact('fornitori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fornitore_id' => 'required|exists:fornitori,id',
            'descrizione' => 'required|string|max:255',
            'prezzo_listino' => 'nullable|numeric|min:0',
            'sconto_1' => 'nullable|numeric|min:0|max:100',
            'sconto_2' => 'nullable|numeric|min:0|max:100',
            'sconto_3' => 'nullable|numeric|min:0|max:100',
        ]);

        ProdottoFornitore::create([
            'fornitore_id' => $request->fornitore_id,
            'descrizione' => $request->descrizione,
            'prezzo_listino' => $request->prezzo_listino ?? 0,
            'sconto_1' => $request->sconto_1 ?? 0,
            'sconto_2' => $request->sconto_2 ?? 0,
            'sconto_3' => $request->sconto_3 ?? 0,
        ]);

        return redirect('/prodotti-fornitore');
    }

    public function edit($id)
    {
        $prodotto = ProdottoFornitore::findOrFail($id);
        $fornitori = Fornitore::all();

        return view('prodotti_fornitore.edit', compact('prodotto', 'fornitori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fornitore_id' => 'required|exists:fornitori,id',
            'descrizione' => 'required|string|max:255',
            'prezzo_listino' => 'nullable|numeric|min:0',
            'sconto_1' => 'nullable|numeric|min:0|max:100',
            'sconto_2' => 'nullable|numeric|min:0|max:100',
            'sconto_3' => 'nullable|numeric|min:0|max:100',
        ]);

        $prodotto = ProdottoFornitore::findOrFail($id);

        $prodotto->update([
            'fornitore_id' => $request->fornitore_id,
            'descrizione' => $request->descrizione,
            'prezzo_listino' => $request->prezzo_listino ?? 0,
            'sconto_1' => $request->sconto_1 ?? 0,
            'sconto_2' => $request->sconto_2 ?? 0,
            'sconto_3' => $request->sconto_3 ?? 0,
        ]);

        return redirect('/prodotti-fornitore');
    }

    public function destroy($id)
    {
        ProdottoFornitore::destroy($id);

        return redirect('/prodotti-fornitore');
    }
}