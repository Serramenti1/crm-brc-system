<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornitore;
use App\Helpers\SearchHelper;

class FornitoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Fornitore::query();

        // 🔍 RICERCA MULTIPLA
        $query = SearchHelper::applyMultiWordSearch(
            $query,
            ['ragione_sociale', 'referente'],
            $request->q
        );

        $fornitori = $query->get();

        return view('fornitori.index', compact('fornitori'));
    }

    public function create()
    {
        return view('fornitori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ragione_sociale' => 'required|string|max:255',
            'referente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'sconto_standard_1' => 'nullable|numeric|min:0|max:100',
            'sconto_standard_2' => 'nullable|numeric|min:0|max:100',
            'sconto_standard_3' => 'nullable|numeric|min:0|max:100',
            'note' => 'nullable|string',
        ]);

        Fornitore::create($request->all());

        return redirect('/fornitori');
    }

    public function edit($id)
    {
        $fornitore = Fornitore::findOrFail($id);

        return view('fornitori.edit', compact('fornitore'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ragione_sociale' => 'required|string|max:255',
            'referente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'sconto_standard_1' => 'nullable|numeric|min:0|max:100',
            'sconto_standard_2' => 'nullable|numeric|min:0|max:100',
            'sconto_standard_3' => 'nullable|numeric|min:0|max:100',
            'note' => 'nullable|string',
        ]);

        $fornitore = Fornitore::findOrFail($id);
        $fornitore->update($request->all());

        return redirect('/fornitori');
    }

    public function destroy($id)
    {
        $fornitore = Fornitore::findOrFail($id);
        $fornitore->delete();

        return redirect('/fornitori');
    }
}