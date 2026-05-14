@include('partials.menu')

<div class="container">

    <div style="margin-bottom:20px;">

        <a href="/preventivi" class="btn btn-azione">
            ← Torna ai preventivi
        </a>

        <a href="/preventivi/{{ $preventivo->id }}" class="btn btn-azione">
            Modifica preventivo
        </a>

    </div>

    <h1>Visualizza Preventivo</h1>

    <table class="tabella-dettaglio">

        <tr>
            <th colspan="2">
                Dati preventivo
            </th>
        </tr>

        <tr>
            <td><strong>Numero</strong></td>
            <td>{{ $preventivo->numero }}</td>
        </tr>

        <tr>
            <td><strong>Cliente</strong></td>
            <td>
                {{ optional(optional($preventivo->commessa)->cliente)->nome }}
                {{ optional(optional($preventivo->commessa)->cliente)->cognome }}
            </td>
        </tr>

        <tr>
            <td><strong>Commessa</strong></td>
            <td>{{ optional($preventivo->commessa)->titolo }}</td>
        </tr>

        <tr>
            <td><strong>Tipo intervento</strong></td>
            <td>{{ optional($preventivo->commessa)->tipo_lavoro }}</td>
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

        @foreach($preventivo->righeProdotti as $riga)
            <tr>
                <td>{{ $riga->descrizione }}</td>

                <td>{{ $riga->quantita }}</td>

                <td>{{ $riga->bene_significativo ? 'Sì' : 'No' }}</td>

                <td>
                    {{ number_format($riga->totale_costo, 2, ',', '.') }} €
                </td>

                <td>
                    {{ number_format($riga->totale_cliente, 2, ',', '.') }} €
                </td>

                <td>
                    {{ number_format($riga->totale_cliente - $riga->totale_costo, 2, ',', '.') }} €
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
            <td>Totale prodotti cliente</td>
            <td>{{ number_format($calcoloIva['totale_prodotti_cliente'], 2, ',', '.') }} €</td>
        </tr>

        <tr>
            <td>Totale servizi cliente</td>
            <td>{{ number_format($calcoloIva['totale_servizi_cliente'], 2, ',', '.') }} €</td>
        </tr>

        <tr>
            <td>Totale imponibile cliente</td>
            <td>{{ number_format($calcoloIva['totale_cliente'], 2, ',', '.') }} €</td>
        </tr>

        @if($calcoloIva['beni_significativi_costo'] > 0)
            <tr>
                <td>Beni significativi costo a noi</td>
                <td>{{ number_format($calcoloIva['beni_significativi_costo'], 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>Markup beni significativi</td>
                <td>{{ number_format($calcoloIva['markup_beni_significativi'], 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>Beni significativi al 10%</td>
                <td>{{ number_format($calcoloIva['beni_significativi_al_10'], 2, ',', '.') }} €</td>
            </tr>

            @if($calcoloIva['beni_significativi_al_22'] > 0)
                <tr>
                    <td>Beni significativi al 22%</td>
                    <td>{{ number_format($calcoloIva['beni_significativi_al_22'], 2, ',', '.') }} €</td>
                </tr>
            @endif
        @endif

        @if($calcoloIva['imponibile_4'] > 0)
            <tr>
                <td>Imponibile 4%</td>
                <td>{{ number_format($calcoloIva['imponibile_4'], 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>IVA 4%</td>
                <td>{{ number_format($calcoloIva['iva_4'], 2, ',', '.') }} €</td>
            </tr>
        @endif

        @if($calcoloIva['imponibile_10'] > 0)
            <tr>
                <td>Imponibile 10%</td>
                <td>{{ number_format($calcoloIva['imponibile_10'], 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>IVA 10%</td>
                <td>{{ number_format($calcoloIva['iva_10'], 2, ',', '.') }} €</td>
            </tr>
        @endif

        @if($calcoloIva['imponibile_22'] > 0)
            <tr>
                <td>Imponibile 22%</td>
                <td>{{ number_format($calcoloIva['imponibile_22'], 2, ',', '.') }} €</td>
            </tr>

            <tr>
                <td>IVA 22%</td>
                <td>{{ number_format($calcoloIva['iva_22'], 2, ',', '.') }} €</td>
            </tr>
        @endif

        <tr>
            <th>Totale IVA</th>
            <th>{{ number_format($calcoloIva['totale_iva'], 2, ',', '.') }} €</th>
        </tr>

        <tr>
            <th>Totale con IVA</th>
            <th>{{ number_format($calcoloIva['totale_con_iva'], 2, ',', '.') }} €</th>
        </tr>

    </table>

</div>