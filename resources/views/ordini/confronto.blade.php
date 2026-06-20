@include('partials.menu')

<div class="container">

    <div style="margin-bottom:20px;">
        <a href="/ordini/{{ $ordine->id }}" class="btn btn-azione">
            ← Torna all'ordine
        </a>
    </div>

    <h1>Confronto Preventivo / Ordine</h1>

    <table class="tabella-dettaglio" style="margin-bottom:20px;">
        <tr>
            <td><strong>Cliente</strong></td>
            <td>
                {{ $ordine->commessa && $ordine->commessa->cliente
                    ? $ordine->commessa->cliente->nome . ' ' . $ordine->commessa->cliente->cognome
                    : '' }}
            </td>
        </tr>
        <tr>
            <td><strong>Preventivo</strong></td>
            <td>{{ $preventivo->numero }}</td>
        </tr>
        <tr>
            <td><strong>Ordine</strong></td>
            <td>{{ $ordine->numero }}</td>
        </tr>
    </table>

    <table class="tabella-lista">

        <tr>
            <th>Fornitore</th>
            <th>Descrizione</th>
            <th>Totale preventivo</th>
            <th>Totale ordine</th>
            <th>Differenza</th>
            <th>Note</th>
        </tr>

        @foreach($confronto as $riga)

            <tr style="
                @if($riga['tipo'] == 'aggiunta') background:#fff3cd; @endif
                @if($riga['tipo'] == 'rimossa') background:#f8d7da; @endif
            ">

                <td>{{ $riga['fornitore'] }}</td>
                <td>{{ $riga['descrizione'] }}</td>

                <td>
                    @if($riga['totale_preventivo'] !== null)
                        {{ number_format($riga['totale_preventivo'], 2, ',', '.') }} €
                    @else
                        -
                    @endif
                </td>

                <td>
                    @if($riga['totale_ordine'] !== null)
                        {{ number_format($riga['totale_ordine'], 2, ',', '.') }} €
                    @else
                        -
                    @endif
                </td>

                <td style="
                    color: {{ $riga['differenza'] > 0 ? 'green' : ($riga['differenza'] < 0 ? 'red' : 'black') }};
                    font-weight:bold;
                ">
                    {{ $riga['differenza'] > 0 ? '+' : '' }}{{ number_format($riga['differenza'], 2, ',', '.') }} €
                </td>

                <td>
                    @if($riga['tipo'] == 'aggiunta')
                        Aggiunta in ordine
                    @elseif($riga['tipo'] == 'rimossa')
                        Rimossa rispetto al preventivo
                    @else
                        -
                    @endif
                </td>

            </tr>

        @endforeach

    </table>

    <br>

    <table class="tabella-dettaglio">
        <tr>
            <th colspan="2">Totali</th>
        </tr>
        <tr>
            <td><strong>Totale preventivo</strong></td>
            <td>{{ number_format($totalePreventivo, 2, ',', '.') }} €</td>
        </tr>
        <tr>
            <td><strong>Totale ordine</strong></td>
            <td>{{ number_format($totaleOrdine, 2, ',', '.') }} €</td>
        </tr>
        <tr>
            <td><strong>Differenza totale</strong></td>
            <td style="
                color: {{ $differenzaTotale > 0 ? 'green' : ($differenzaTotale < 0 ? 'red' : 'black') }};
                font-weight:bold;
            ">
                {{ $differenzaTotale > 0 ? '+' : '' }}{{ number_format($differenzaTotale, 2, ',', '.') }} €
            </td>
        </tr>
    </table>

</div>