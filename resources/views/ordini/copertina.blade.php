<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Copertina {{ $ordine->numero }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: white;
            padding: 15mm;
        }

        .testata {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .testata h1 {
            font-size: 16px;
            font-weight: bold;
        }

        .testata h2 {
            font-size: 13px;
            font-weight: normal;
            margin-top: 4px;
        }

        .sezione {
            border: 1px solid #000;
            margin-bottom: 8px;
        }

        .sezione-titolo {
            background: #1f2937;
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sezione-corpo {
            padding: 8px 10px;
        }

        .riga-campo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        .riga-campo:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .etichetta {
            font-weight: bold;
            min-width: 200px;
        }

        .linea-data {
            border-bottom: 1px solid #000;
            min-width: 120px;
            height: 16px;
            display: inline-block;
        }

        .linea-testo {
            border-bottom: 1px solid #000;
            min-width: 250px;
            height: 16px;
            display: inline-block;
        }

        .checkbox-stampa {
            width: 13px;
            height: 13px;
            border: 1.5px solid #000;
            display: inline-block;
            vertical-align: middle;
            margin-right: 4px;
            flex-shrink: 0;
        }

        .flag-gruppo {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .flag-item {
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        /* DATE GRIGLIA */
        .date-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            padding: 8px 10px;
        }

        .date-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .date-item .etichetta {
            min-width: auto;
            font-size: 11px;
        }

        /* CHECKLIST */
        .checklist-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        .checklist-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* TABELLA PRODOTTI */
        .tabella-prodotti {
            width: 100%;
            border-collapse: collapse;
        }

        .tabella-prodotti th {
            background: #e5e7eb;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #000;
            font-size: 11px;
        }

        .tabella-prodotti td {
            padding: 6px 8px;
            border: 1px solid #000;
            font-size: 11px;
            vertical-align: middle;
        }

        .box-note {
            min-height: 50px;
            border: 1px solid #ccc;
            padding: 4px;
        }

        @media print {
            body { padding: 10mm; }
            @page { size: A4; margin: 10mm; }
        }
    </style>
</head>
<body>

    {{-- TESTATA --}}
<div class="testata">
    <h1 style="font-size:32px; font-weight:bold; margin-bottom:4px;">
    {{ $ordine->commessa && $ordine->commessa->cliente
        ? strtoupper($ordine->commessa->cliente->nomeVisualizzato())
        : '' }}
</h1>
    <h2 style="font-size:14px; font-weight:bold; margin-bottom:4px;">
        ORDINE {{ $ordine->numero }}
    </h2>
    <div style="font-size:12px; margin-bottom:2px;">
        @if($ordine->commessa?->titolo)
            {{ strtoupper($ordine->commessa->titolo) }}
        @endif
    </div>
    @if($ordine->commessa?->indirizzo_lavoro)
        <div style="font-size:11px; margin-top:2px;">
            {{ strtoupper($ordine->commessa->indirizzo_lavoro) }}
            @if($ordine->commessa?->citta_lavoro)
                — {{ strtoupper($ordine->commessa->citta_lavoro) }}
            @endif
        </div>
    @endif
</div>

    {{-- DATE --}}
    <div class="sezione">
        <div class="sezione-titolo">Date</div>
        <div class="date-grid">
            <div class="date-item">
                <span class="etichetta">Posa stimata sett.</span>
                <span class="linea-data"></span>
            </div>
            <div class="date-item">
                <span class="etichetta">ACC Data</span>
                <span class="linea-data"></span>
            </div>
            <div class="date-item">
                <span class="etichetta">Acconto merce pronta Data</span>
                <span class="linea-data"></span>
            </div>
        </div>
    </div>

    {{-- CHECKLIST CLIENTE --}}
    <div class="sezione">
        <div class="sezione-titolo">
    {{ $ordine->commessa && $ordine->commessa->cliente
        ? strtoupper($ordine->commessa->cliente->nomeVisualizzato())
        : 'CLIENTE' }}
</div>
        <div class="sezione-corpo">
            <div class="checklist-item">
                <span class="checkbox-stampa"></span>
                <span>Cliente ha consegnato pratica edilizia</span>
            </div>
            <div class="checklist-item">
                <span class="checkbox-stampa"></span>
                <span>Verifica massimali</span>
            </div>
            <div class="checklist-item">
                <span class="checkbox-stampa"></span>
                <span>Verifica se necessaria pratica ENEA</span>
            </div>
        </div>
    </div>

    {{-- PRODOTTI --}}
    <div class="sezione">
        <div class="sezione-titolo">Prodotti e controllo lavorazione</div>
        <div class="sezione-corpo" style="padding:0;">
            <table class="tabella-prodotti">
                <tr>
                    <th style="width:40%;">Prodotto</th>
                    <th style="width:20%;">Fornitore</th>
                    <th style="width:13%; text-align:center;">Inviato ordine</th>
                    <th style="width:13%; text-align:center;">C.O. ricevuta</th>
                    <th style="width:14%; text-align:center;">In produzione</th>
                </tr>
                @foreach($ordine->righe->sortBy('ordine_visualizzazione') as $riga)
                <tr>
                    <td>
                        <strong>{{ $riga->descrizione }}</strong>
                        <br><small>Qta: {{ $riga->quantita }}</small>
                    </td>
                    <td>{{ $riga->fornitore?->ragione_sociale ?? '-' }}</td>
                    <td style="text-align:center;"><span class="checkbox-stampa"></span></td>
                    <td style="text-align:center;"><span class="checkbox-stampa"></span></td>
                    <td style="text-align:center;"><span class="checkbox-stampa"></span></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    {{-- SMALTIMENTO --}}
    <div class="sezione">
        <div class="sezione-titolo">Smaltimento imballi e scarti lavorazione</div>
        <div class="sezione-corpo">
            <div class="flag-gruppo">
                <span class="flag-item">
                    <span class="checkbox-stampa"></span>
                    NO (rimane cantiere)
                </span>
                <span class="flag-item">
                    <span class="checkbox-stampa"></span>
                    SI
                </span>
            </div>
        </div>
    </div>

    {{-- DETRAZIONI --}}
    <div class="sezione">
        <div class="sezione-titolo">Detrazioni fiscali</div>
        <div class="sezione-corpo">

            @php
                $haDetrazione = !empty($ordine->commessa?->tipo_detrazione);
            @endphp

            <div class="riga-campo">
                <span class="etichetta">Detrazioni fiscali:</span>
                <span class="flag-gruppo">
                    <span class="flag-item">
                        <span class="checkbox-stampa" style="{{ $haDetrazione ? 'background:#000;' : '' }}"></span>
                        SI
                    </span>
                    <span class="flag-item">
                        <span class="checkbox-stampa" style="{{ !$haDetrazione ? 'background:#000;' : '' }}"></span>
                        NO
                    </span>
                </span>
            </div>

            <div class="riga-campo">
                <span class="etichetta">Tipo detrazione:</span>
                <span>{{ $ordine->commessa?->tipo_detrazione ?? '' }}</span>
                @if(!$haDetrazione)
                    <span class="linea-testo"></span>
                @endif
            </div>

            <div class="riga-campo">
                <span class="etichetta">Inoltro:</span>
                <span class="linea-testo"></span>
            </div>

        </div>
    </div>

    {{-- NOTE --}}
    <div class="sezione">
        <div class="sezione-titolo">Note</div>
        <div class="sezione-corpo">
            <div class="box-note"></div>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>

</body>
</html>