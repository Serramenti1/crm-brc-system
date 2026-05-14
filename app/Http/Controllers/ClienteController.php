<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Helpers\SearchHelper;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::with('commesse');

        $query = SearchHelper::applyMultiWordSearch(
            $query,
            ['nome', 'cognome'],
            $request->q
        );

        $clienti = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('clienti.index', compact('clienti'));
    }

    public function create()
    {
        return view('clienti.create');
    }

    public function store(Request $request)
{
    $request->validate(

        [
            'email' => 'nullable|unique:clienti,email',
            'codice_fiscale' => 'nullable|unique:clienti,codice_fiscale',
        ],

        [
            'email.unique' => 'Esiste già un cliente con questa email.',

            'codice_fiscale.unique' =>
                'Esiste già un cliente con questo codice fiscale.',
        ]

    );

    Cliente::create($request->all());

    return redirect('/clienti')
        ->with('success', 'Cliente creato correttamente.');
}

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clienti.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clienti.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
{
    $cliente = Cliente::findOrFail($id);

    $request->validate(

        [
            'email' =>
                'nullable|unique:clienti,email,' . $cliente->id,

            'codice_fiscale' =>
                'nullable|unique:clienti,codice_fiscale,' . $cliente->id,
        ],

        [
            'email.unique' =>
                'Esiste già un cliente con questa email.',

            'codice_fiscale.unique' =>
                'Esiste già un cliente con questo codice fiscale.',
        ]

    );

    $cliente->update($request->all());

    return redirect('/clienti')
        ->with('success', 'Cliente aggiornato correttamente.');
}

    public function destroy($id)
{
    $cliente = Cliente::with('commesse.preventivi.ordine')->findOrFail($id);

    if ($cliente->commesse->count() > 0) {

        return redirect('/clienti')
            ->with('error', 'Non puoi eliminare questo cliente perché ha commesse collegate.');
    }

    $cliente->delete();

    return redirect('/clienti')
        ->with('success', 'Cliente eliminato');
}
}