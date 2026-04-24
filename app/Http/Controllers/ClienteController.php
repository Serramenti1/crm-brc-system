<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        $clienti = Cliente::all();
        return view('clienti.index', compact('clienti'));
    }

    public function create()
    {
        return view('clienti.create');
    }

    public function store(Request $request)
    {
        Cliente::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'indirizzo' => $request->indirizzo,
            'citta' => $request->citta,
            'cap' => $request->cap,
            'provincia' => $request->provincia,
            'codice_fiscale' => $request->codice_fiscale,
            'partita_iva' => $request->partita_iva,
        ]);

        return redirect('/clienti');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clienti.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->update([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'indirizzo' => $request->indirizzo,
            'citta' => $request->citta,
            'cap' => $request->cap,
            'provincia' => $request->provincia,
            'codice_fiscale' => $request->codice_fiscale,
            'partita_iva' => $request->partita_iva,
        ]);

        return redirect('/clienti');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect('/clienti');
    }
}