<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImpostazioneIva;
use App\Models\Detrazione;
use App\Models\ServizioExtra;
use App\Models\TipoIntervento;

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
        $detrazioni = Detrazione::orderBy('nome')->get();

        return view('impostazioni.detrazioni', compact('detrazioni'));
    }

    public function storeDetrazione(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'percentuale' => 'required|numeric|min:0|max:100',
        ]);

        Detrazione::create([
            'nome' => $request->nome,
            'percentuale' => $request->percentuale,
            'attiva' => $request->has('attiva') ? 1 : 0,
        ]);

        return redirect('/impostazioni/detrazioni')->with('success', 'Detrazione salvata');
    }

    public function updateDetrazione(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'percentuale' => 'required|numeric|min:0|max:100',
        ]);

        $detrazione = Detrazione::findOrFail($id);

        $detrazione->update([
            'nome' => $request->nome,
            'percentuale' => $request->percentuale,
            'attiva' => $request->has('attiva') ? 1 : 0,
        ]);

        return redirect('/impostazioni/detrazioni')->with('success', 'Detrazione aggiornata');
    }

    public function servizi()
    {
        $servizi = ServizioExtra::orderBy('nome')->get();

        return view('impostazioni.servizi', compact('servizi'));
    }

    public function storeServizio(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'costo_brc' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $costo = (float) ($request->costo_brc ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);
        $prezzoCliente = $costo * (1 + ($ricarico / 100));

        ServizioExtra::create([
            'nome' => $request->nome,
            'costo_brc' => $costo,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente' => $prezzoCliente,
            'attivo' => $request->has('attivo') ? 1 : 0,
            'note' => $request->note,
        ]);

        return redirect('/impostazioni/servizi')->with('success', 'Servizio extra salvato');
    }

    public function updateServizio(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'costo_brc' => 'nullable|numeric|min:0',
            'ricarico_percentuale' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $servizio = ServizioExtra::findOrFail($id);

        $costo = (float) ($request->costo_brc ?? 0);
        $ricarico = (float) ($request->ricarico_percentuale ?? 0);
        $prezzoCliente = $costo * (1 + ($ricarico / 100));

        $servizio->update([
            'nome' => $request->nome,
            'costo_brc' => $costo,
            'ricarico_percentuale' => $ricarico,
            'prezzo_cliente' => $prezzoCliente,
            'attivo' => $request->has('attivo') ? 1 : 0,
            'note' => $request->note,
        ]);

        return redirect('/impostazioni/servizi')->with('success', 'Servizio extra aggiornato');
    }

    public function tipiIntervento()
    {
        $tipiIntervento = TipoIntervento::with('ivaPrincipale', 'ivaSecondaria')
            ->orderBy('nome')
            ->get();

        $iva = ImpostazioneIva::where('attiva', 1)
            ->orderBy('nome')
            ->get();

        return view('impostazioni.tipi_intervento', compact('tipiIntervento', 'iva'));
    }

    public function storeTipoIntervento(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'modalita_iva' => 'required|in:iva_unica,beni_significativi',
            'impostazione_iva_id' => 'nullable|exists:impostazioni_iva,id',
            'impostazione_iva_secondaria_id' => 'nullable|exists:impostazioni_iva,id',
            'note' => 'nullable|string',
        ]);

        TipoIntervento::create([
            'nome' => $request->nome,
            'attivo' => $request->has('attivo') ? 1 : 0,
            'modalita_iva' => $request->modalita_iva,
            'impostazione_iva_id' => $request->impostazione_iva_id,
            'impostazione_iva_secondaria_id' => $request->impostazione_iva_secondaria_id,
            'note' => $request->note,
        ]);

        return redirect('/impostazioni/tipi-intervento')
            ->with('success', 'Tipo intervento salvato');
    }

    public function updateTipoIntervento(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'modalita_iva' => 'required|in:iva_unica,beni_significativi',
            'impostazione_iva_id' => 'nullable|exists:impostazioni_iva,id',
            'impostazione_iva_secondaria_id' => 'nullable|exists:impostazioni_iva,id',
            'note' => 'nullable|string',
        ]);

        $tipo = TipoIntervento::findOrFail($id);

        $tipo->update([
            'nome' => $request->nome,
            'attivo' => $request->has('attivo') ? 1 : 0,
            'modalita_iva' => $request->modalita_iva,
            'impostazione_iva_id' => $request->impostazione_iva_id,
            'impostazione_iva_secondaria_id' => $request->impostazione_iva_secondaria_id,
            'note' => $request->note,
        ]);

        return redirect('/impostazioni/tipi-intervento')
            ->with('success', 'Tipo intervento aggiornato');
    }
}