<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImpostazioneIva;
use App\Models\Detrazione;

class ImpostazioneController extends Controller
{
    public function index()
    {
        return view('impostazioni.index');
    }

    public function iva()
    {
        $iva = ImpostazioneIva::orderBy('nome')->get();

        return view('impostazioni.iva', compact('iva'));
    }

    public function storeIva(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'aliquota' => 'required|numeric|min:0|max:100',
        ]);

        ImpostazioneIva::create([
            'nome' => $request->nome,
            'aliquota' => $request->aliquota,
            'attiva' => $request->has('attiva') ? 1 : 0,
        ]);

        return redirect('/impostazioni/iva')->with('success', 'IVA salvata');
    }

    public function updateIva(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'aliquota' => 'required|numeric|min:0|max:100',
        ]);

        $iva = ImpostazioneIva::findOrFail($id);

        $iva->update([
            'nome' => $request->nome,
            'aliquota' => $request->aliquota,
            'attiva' => $request->has('attiva') ? 1 : 0,
        ]);

        return redirect('/impostazioni/iva')->with('success', 'IVA aggiornata');
    }

    public function detrazioni()
    {
        $detrazioni = Detrazione::with('varianti')->orderBy('nome')->get();

        return view('impostazioni.detrazioni', compact('detrazioni'));
    }

    public function storeDetrazione(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        Detrazione::create([
            'nome' => $request->nome,
            'attiva' => $request->has('attiva') ? 1 : 0,
        ]);

        return redirect('/impostazioni/detrazioni')->with('success', 'Detrazione salvata');
    }

    public function updateDetrazione(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $detrazione = Detrazione::findOrFail($id);

        $detrazione->update([
            'nome' => $request->nome,
            'attiva' => $request->has('attiva') ? 1 : 0,
        ]);

        return redirect('/impostazioni/detrazioni')->with('success', 'Detrazione aggiornata');
    }
}