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
            <th>Prodotto prev.</th>
            <th>Servizi prev.</th>
            <th>Prodotto ord.</th>
            <th>Servizi ord.</th>
            <th>Δ Prodotto</th>
            <th>Δ Servizi</th>
            <th>Δ Totale</th>
            <th>Note</th>
        </tr>

        @foreach($confronto as $riga)

            @php
                $quantitaZero = isset($riga['quantita_preventivo']) &&
                                $riga['quantita_preventivo'] !== null &&
                                $riga['quantita_preventivo'] == 0;

                $sfondo = '';
                if ($quantitaZero) {
                    $sfondo = 'background:#f8d7da;'; // rosso - variante a quantità 0
                } elseif ($riga['tipo'] == 'aggiunta') {
                    $sfondo = 'background:#fff3cd;'; // giallo - aggiunta in ordine
                } elseif ($riga['tipo'] == 'rimossa') {
                    $sfondo = 'background:#f8d7da;'; // rosso - rimossa dall'ordine
                }
            @endphp

            <tr style="{{ $sfondo }}">

                <td>{{ $riga['fornitore'] }}</td>

                <td>
                    {{ $riga['descrizione'] }}
                    @if($quantitaZero)
                        <br><small style="color:red;">(variante a preventivo - quantità 0)</small>
                    @endif
                </td>

                {{-- PRODOTTO PREVENTIVO --}}
                <td>
                    @if($riga['prodotto_preventivo'] !== null)
                        {{ number_format($riga['prodotto_preventivo'], 2, ',', '.') }} €
                    @else
                        -
                    @endif
                </td>

                {{-- SERVIZI PREVENTIVO --}}
                <td>
                    @if($riga['servizi_preventivo'] !== null)
                        {{ number_format($riga['servizi_preventivo'], 2, ',', '.') }} €
                    @else
                        -
                    @endif
                </td>

                {{-- PRODOTTO ORDINE --}}
                <td>
                    @if($riga['prodotto_ordine'] !== null)
                        {{ number_format($riga['prodotto_ordine'], 2, ',', '.') }} €
                    @else
                        -
                    @endif
                </td>

                {{-- SERVIZI ORDINE --}}
                <td>
                    @if($riga['servizi_ordine'] !== null)
                        {{ number_format($riga['servizi_ordine'], 2, ',', '.') }} €
                    @else
                        -
                    @endif
                </td>

                {{-- DIFFERENZA PRODOTTO --}}
                <td style="
                    font-weight:bold;
                    color: {{ $riga['diff_prodotto'] > 0 ? 'green' : ($riga['diff_prodotto'] < 0 ? 'red' : 'black') }};
                ">
                    @if($riga['diff_prodotto'] != 0)
                        {{ $riga['diff_prodotto'] > 0 ? '+' : '' }}{{ number_format($riga['diff_prodotto'], 2, ',', '.') }} €
                    @else
                        0,00 €
                    @endif
                </td>

                {{-- DIFFERENZA SERVIZI --}}
                <td style="
                    font-weight:bold;
                    color: {{ $riga['diff_servizi'] > 0 ? 'green' : ($riga['diff_servizi'] < 0 ? 'red' : 'black') }};
                ">
                    @if($riga['diff_servizi'] != 0)
                        {{ $riga['diff_servizi'] > 0 ? '+' : '' }}{{ number_format($riga['diff_servizi'], 2, ',', '.') }} €
                    @else
                        0,00 €
                    @endif
                </td>

                {{-- DIFFERENZA TOTALE --}}
                <td style="
                    font-weight:bold;
                    color: {{ $riga['differenza'] > 0 ? 'green' : ($riga['differenza'] < 0 ? 'red' : 'black') }};
                ">
                    {{ $riga['differenza'] > 0 ? '+' : '' }}{{ number_format($riga['differenza'], 2, ',', '.') }} €
                </td>

                {{-- NOTE --}}
                <td>
                    @if($riga['tipo'] == 'aggiunta')
                        Aggiunta in ordine
                    @elseif($riga['tipo'] == 'rimossa')
                        Rimossa rispetto al preventivo
                    @elseif($quantitaZero)
                        Variante a preventivo (quantità 0)
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
                font-weight:bold;
                color: {{ $differenzaTotale > 0 ? 'green' : ($differenzaTotale < 0 ? 'red' : 'black') }};
            ">
                {{ $differenzaTotale > 0 ? '+' : '' }}{{ number_format($differenzaTotale, 2, ',', '.') }} €
            </td>
        </tr>
    </table>

</div>