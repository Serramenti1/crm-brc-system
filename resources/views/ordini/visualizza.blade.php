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
                {{ optional(optional($ordine->commessa)->cliente)->nome }}
                {{ optional(optional($ordine->commessa)->cliente)->cognome }}
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

    <h2>Prodotti e servizi</h2>

    <table class="tabella-lista">

        <tr>
            <th>Descrizione</th>
            <th>Quantità</th>
            <th>Bene significativo</th>
            <th>Imponibile noi</th>
            <th>Imponibile cliente</th>
            <th>Markup</th>
            <th>Servizi</th>
        </tr>

        @foreach($ordine->righe as $riga)
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

                <td>
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