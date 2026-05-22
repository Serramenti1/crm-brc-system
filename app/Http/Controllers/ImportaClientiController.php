<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

class ImportaClientiController extends Controller
{
    // =====================================================
    // PAGINA IMPORTAZIONE EXCEL
    // =====================================================

    public function index()
    {
        return view('impostazioni.importa-clienti');
    }

    // =====================================================
    // CARICAMENTO FILE E ANTEPRIMA
    // =====================================================

    public function anteprima(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        // =====================================================
        // SALVATAGGIO FILE TEMPORANEO
        // =====================================================

        $path = $request
            ->file('file_excel')
            ->store('importazioni_clienti');

        // =====================================================
        // LETTURA FILE EXCEL
        // =====================================================

        $spreadsheet = IOFactory::load(
        Storage::disk('local')->path($path)
        );

        $sheet = $spreadsheet->getActiveSheet();

        $righe = $sheet->toArray();

        // =====================================================
        // RECUPERO INTESTAZIONI
        // =====================================================

        $intestazioni = $righe[0] ?? [];

        // =====================================================
        // ANTEPRIMA PRIME 5 RIGHE
        // =====================================================

        $anteprima = array_slice($righe, 1, 5);

        return view(
            'impostazioni.importa-clienti-mappa',
            compact(
                'path',
                'intestazioni',
                'anteprima'
            )
        );
    }

    // =====================================================
    // IMPORTAZIONE CLIENTI
    // =====================================================

    public function importa(Request $request)
    {
        $request->validate([
            'path' => 'required',
            'mappa' => 'required|array',
        ]);

        // =====================================================
        // LETTURA FILE EXCEL
        // =====================================================

        $spreadsheet = IOFactory::load(
        Storage::disk('local')->path($request->path)
        );

        $sheet = $spreadsheet->getActiveSheet();

        $righe = $sheet->toArray();

        // =====================================================
        // RIMOZIONE RIGA INTESTAZIONI
        // =====================================================

        unset($righe[0]);

        $importati = 0;
        $saltati = 0;

        // =====================================================
        // CICLO IMPORTAZIONE
        // =====================================================

        foreach ($righe as $riga) {

            $dati = [];

            // =====================================================
            // MAPPATURA COLONNE
            // =====================================================

            foreach ($request->mappa as $campoCliente => $indiceColonna) {

                if (
                    $indiceColonna !== null &&
                    $indiceColonna !== ''
                ) {

                    $dati[$campoCliente] =
                        $riga[$indiceColonna] ?? null;
                }
            }

            // =====================================================
            // SALTA RIGHE VUOTE
            // =====================================================

            if (
                empty($dati['nome']) &&
                empty($dati['cognome'])
            ) {
                continue;
            }

            // =====================================================
            // CONTROLLO EMAIL DUPLICATA
            // =====================================================

            if (
                !empty($dati['email']) &&
                Cliente::where('email', $dati['email'])->exists()
            ) {

                $saltati++;
                continue;
            }

            // =====================================================
            // CONTROLLO CODICE FISCALE DUPLICATO
            // =====================================================

            if (
                !empty($dati['codice_fiscale']) &&
                Cliente::where(
                    'codice_fiscale',
                    $dati['codice_fiscale']
                )->exists()
            ) {

                $saltati++;
                continue;
            }

            // =====================================================
            // CREAZIONE CLIENTE
            // =====================================================

            Cliente::create($dati);

            $importati++;
        }

        // =====================================================
        // RITORNO CON RISULTATO
        // =====================================================

        return redirect('/clienti')
            ->with(
                'success',
                'Importazione completata. Clienti importati: '
                . $importati .
                '. Saltati: '
                . $saltati .
                '.'
            );
    }
}