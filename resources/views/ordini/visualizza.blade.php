@include('partials.menu')

<div class="container">

    <div style="margin-bottom:20px;">

        <a href="/ordini/stato/{{ $ordine->stato }}" class="btn btn-azione">
            ← Torna agli ordini
        </a>

        <a href="/ordini/{{ $ordine->id }}" class="btn btn-azione">
            Modifica ordine
        </a>

    </div>

    <h1>Visualizza Ordine</h1>

    <table class="tabella-dettaglio">

        <tr>
            <th colspan="2">
                Dati ordine
            </th>
        </tr>

        <tr>
            <td><strong>Numero ordine</strong></td>
            <td>{{ $ordine->numero }}</td>
        </tr>

        <tr>
    <td><strong>Cliente</strong></td>
    <td>
        {{ optional(optional($ordine->commessa)->cliente)->nomeVisualizzato() }}
    </td>
</tr>

        <tr>
            <td><strong>Commessa</strong></td>
            <td>{{ optional($ordine->commessa)->titolo }}</td>
        </tr>

        <tr>
            <td><strong>Tipo intervento</strong></td>
            <td>{{ optional(optional($ordine->commessa)->tipoIntervento)->nome }}</td>
        </tr>

        <tr>
            <td><strong>Stato</strong></td>
            <td>
                @if($ordine->stato == 'preparazione_contratto')
                    Preparazione contratto
                @elseif($ordine->stato == 'in_lavorazione')
                    In lavorazione
                @elseif($ordine->stato == 'completo_attesa_merce')
                    Completo - attesa merce
                @elseif($ordine->stato == 'attesa_saldo_merce')
                    Attesa saldo merce
                @elseif($ordine->stato == 'programmare_posa')
                    Programmare posa
                @elseif($ordine->stato == 'concluso')
                    Concluso
                @elseif($ordine->stato == 'archiviato')
                    Archiviato
                @else
                    {{ $ordine->stato }}
                @endif
            </td>
        </tr>

    </table>
    
<h2>Documenti ordine</h2>

<table class="tabella-dettaglio">

    <tr>
        <th>Documento</th>
        <th>PDF</th>
    </tr>

    <tr>
        <td>Foglio smaltimento</td>

        <td>

            @if($ordine->pdf_foglio_smaltimento)

                <a href="{{ asset('storage/' . $ordine->pdf_foglio_smaltimento) }}"
                   target="_blank"
                   class="btn btn-azione">
                    Apri PDF
                </a>

            @else
                -
            @endif

        </td>
    </tr>

    <tr>
        <td>Contratto copia posatori</td>

        <td>

            @if($ordine->pdf_contratto_posatori)

                <a href="{{ asset('storage/' . $ordine->pdf_contratto_posatori) }}"
                   target="_blank"
                   class="btn btn-azione">
                    Apri PDF
                </a>

            @else
                -
            @endif

        </td>
    </tr>

    <tr>
        <td>Contratto vendita</td>

        <td>

            @if($ordine->pdf_contratto_vendita)

                <a href="{{ asset('storage/' . $ordine->pdf_contratto_vendita) }}"
                   target="_blank"
                   class="btn btn-azione">
                    Apri PDF
                </a>

            @else
                -
            @endif

        </td>
    </tr>

</table>

<br>
    <h2>Prodotti e servizi</h2>

    <table class="tabella-lista">

        <tr>
    <tr>
    <th>Descrizione</th>
    <th>Quantità</th>
    <th>Bene significativo</th>
    <th>Imponibile noi</th>
    <th>Imponibile cliente</th>
    <th>Ricarico</th>
    <th>Markup</th>
    <th>Servizi</th>
</tr>

@foreach($ordine->righe->sortBy('ordine_visualizzazione') as $riga)

    @php
        $ricaricoRiga = (float) ($riga->ricarico_percentuale ?? 0);
        $sogliaRossa = (float) ($impostazioni->margine_soglia_rossa ?? 40);
        $sogliaVerde = (float) ($impostazioni->margine_soglia_verde ?? 50);

        if ($ricaricoRiga < $sogliaRossa) {
            $coloreRicarico = '#dc3545';
        } elseif ($ricaricoRiga < $sogliaVerde) {
            $coloreRicarico = '#fd7e14';
        } else {
            $coloreRicarico = '#28a745';
        }
    @endphp

    <tr>
        <td>{{ $riga->descrizione }}</td>

        <td>{{ $riga->quantita }}</td>

        <td>{{ $riga->bene_significativo ? 'Sì' : 'No' }}</td>

                <td>
            {{ number_format($riga->totale_costo ?? 0, 2, ',', '.') }} €
        </td>

        <td>
            {{ number_format($riga->totale_cliente ?? $riga->imponibile, 2, ',', '.') }} €
        </td>

        <td style="color:{{ $coloreRicarico }}; font-weight:bold;">
            {{ number_format($ricaricoRiga, 2, ',', '.') }}%
        </td>

        <td style="color:{{ $coloreRicarico }}; font-weight:bold;">
         {{ number_format(($riga->totale_cliente ?? $riga->imponibile) - ($riga->totale_costo ?? 0), 2, ',', '.') }} €
        </td>

        <td>
            @foreach($riga->servizi as $servizio)
                {{ $servizio->tipo_servizio }}
                -
                {{ number_format($servizio->prezzo_cliente * $riga->quantita, 2, ',', '.') }} €
                <br>
            @endforeach
        </td>
    </tr>
@endforeach

        </table>

    @php
        $totaleCostoCantiere = $ordine->righe->sum('totale_costo');
        $totaleClienteCantiere = $ordine->righe->sum(function ($riga) {
            return $riga->totale_cliente ?? $riga->imponibile ?? 0;
        });
        $markupTotaleCantiere = $totaleClienteCantiere - $totaleCostoCantiere;

        $ricaricoTotaleCantiere = $totaleCostoCantiere > 0
            ? ($markupTotaleCantiere / $totaleCostoCantiere) * 100
            : 0;

        if ($ricaricoTotaleCantiere < $sogliaRossa) {
            $coloreRicaricoTotale = '#dc3545';
        } elseif ($ricaricoTotaleCantiere < $sogliaVerde) {
            $coloreRicaricoTotale = '#fd7e14';
        } else {
            $coloreRicaricoTotale = '#28a745';
        }
    @endphp

    <h2>Riepilogo cantiere</h2>

    <table class="tabella-dettaglio">

        <tr>
            <td><strong>Totale costo prodotti</strong></td>
            <td>{{ number_format($totaleCostoCantiere, 2, ',', '.') }} €</td>
        </tr>

        <tr>
            <td><strong>Totale imponibile cliente prodotti</strong></td>
            <td>{{ number_format($totaleClienteCantiere, 2, ',', '.') }} €</td>
        </tr>

        <tr>
            <td><strong>Ricarico totale cantiere</strong></td>
            <td style="color:{{ $coloreRicaricoTotale }}; font-weight:bold;">
                {{ number_format($ricaricoTotaleCantiere, 2, ',', '.') }}%
            </td>
        </tr>

        <tr>
            <td><strong>Markup totale cantiere</strong></td>
            <td style="color:{{ $coloreRicaricoTotale }}; font-weight:bold;">
                {{ number_format($markupTotaleCantiere, 2, ',', '.') }} €
            </td>
        </tr>

    </table>

    <h2>Riepilogo IVA</h2>

    <table class="tabella-dettaglio">

        <tr>
            <th>Voce</th>
            <th>Importo</th>
        </tr>

        <tr>
            <td>Totale imponibile ordine</td>
            <td>{{ number_format($ordine->imponibile ?? 0, 2, ',', '.') }} €</td>
        </tr>

        @if(($ordine->imponibile_4 ?? 0) > 0)
            <tr>
                <td>Imponibile 4%</td>
                <td>{{ number_format($ordine->imponibile_4, 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>IVA 4%</td>
                <td>{{ number_format($ordine->iva_4, 2, ',', '.') }} €</td>
            </tr>
        @endif

        @if(($ordine->imponibile_10 ?? 0) > 0)
            <tr>
                <td>Imponibile 10%</td>
                <td>{{ number_format($ordine->imponibile_10, 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>IVA 10%</td>
                <td>{{ number_format($ordine->iva_10, 2, ',', '.') }} €</td>
            </tr>
        @endif

        @if(($ordine->imponibile_22 ?? 0) > 0)
            <tr>
                <td>Imponibile 22%</td>
                <td>{{ number_format($ordine->imponibile_22, 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>IVA 22%</td>
                <td>{{ number_format($ordine->iva_22, 2, ',', '.') }} €</td>
            </tr>
        @endif

        <tr>
            <th>Totale IVA</th>
            <th>{{ number_format($ordine->totale_iva ?? 0, 2, ',', '.') }} €</th>
        </tr>

        <tr>
            <th>Totale con IVA</th>
            <th>{{ number_format($ordine->totale_con_iva ?? 0, 2, ',', '.') }} €</th>
        </tr>

    </table>

</div>