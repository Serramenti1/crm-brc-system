<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornitore;

class FornitoreController extends Controller
{
    public function index()
    {
        $fornitori = Fornitore::all();
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
        $fornitore = Fornitore::findOrFail($id);

        $fornitore->update($request->all());

        return redirect('/fornitori');
    }

    public function destroy($id)
    {
        Fornitore::destroy($id);

        return redirect('/fornitori');
    }
}