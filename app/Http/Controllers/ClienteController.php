<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Helpers\SearchHelper;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        $query = SearchHelper::applyMultiWordSearch(
            $query,
            ['nome', 'cognome'],
            $request->q
        );

        $clienti = $query->get();

        return view('clienti.index', compact('clienti'));
    }

    public function create()
    {
        return view('clienti.create');
    }

    public function store(Request $request)
    {
        Cliente::create($request->all());
        return redirect('/clienti');
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
        $cliente->update($request->all());
        return redirect('/clienti');
    }

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return redirect('/clienti');
    }
}