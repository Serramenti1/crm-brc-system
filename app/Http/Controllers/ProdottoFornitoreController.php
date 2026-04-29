<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdottoFornitore;
use App\Models\Fornitore;

class ProdottoFornitoreController extends Controller
{
    public function index()
    {
        $prodotti = ProdottoFornitore::with('fornitore')->get();
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
            'prezzo_listino' => 'required|numeric|min:0',
            'sconto_1' => 'nullable|numeric|min:0|max:100',
            'sconto_2' => 'nullable|numeric|min:0|max:100',
            'sconto_3' => 'nullable|numeric|min:0|max:100',
        ]);

        ProdottoFornitore::create($request->all());

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
        $prodotto = ProdottoFornitore::findOrFail($id);

        $prodotto->update($request->all());

        return redirect('/prodotti-fornitore');
    }

    public function destroy($id)
    {
        ProdottoFornitore::destroy($id);

        return redirect('/prodotti-fornitore');
    }
}