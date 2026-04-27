@include('partials.menu')

<h1>Dettaglio Preventivo</h1>

<p><strong>Numero:</strong> {{ $preventivo->numero }}</p>
<p><strong>Descrizione:</strong> {{ $preventivo->descrizione }}</p>

<p>
    <strong>Cliente:</strong>
    {{ $preventivo->commessa && $preventivo->commessa->cliente ? $preventivo->commessa->cliente->nome . ' ' . $preventivo->commessa->cliente->cognome : '' }}
</p>

<p>
    <strong>Commessa:</strong>
    {{ $preventivo->commessa ? $preventivo->commessa->titolo : '' }}
</p>

<hr>

<h2>Riepilogo Preventivo</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Listino</th>
    <th>Prodotti</th>
    <th>Servizi</th>
    <th>Totale</th>
    <th>Sconto</th>
    <th>Costo</th>
    <th>Utile</th>
</tr>
<tr>
    <td>{{ number_format($preventivo->totale_listino_prodotti, 2, ',', '.') }} €</td>
    <td>{{ number_format($preventivo->totale_netto_prodotti, 2, ',', '.') }} €</td>
    <td>{{ number_format($preventivo->totale_servizi_cliente, 2, ',', '.') }} €</td>
    <td>{{ number_format($preventivo->totale_cliente_finale, 2, ',', '.') }} €</td>
    <td>{{ number_format($preventivo->sconto_medio_cliente, 2, ',', '.') }}%</td>
    <td>{{ number_format($preventivo->totale_costo_brc, 2, ',', '.') }} €</td>
    <td>{{ number_format($preventivo->utile_totale, 2, ',', '.') }} €</td>
</tr>
</table>

<hr>

<h2>Prodotti</h2>

<!-- AGGIUNTA PRODOTTO -->
<details>
    <summary><strong>+ Aggiungi riga prodotto</strong></summary>

    <form method="POST" action="/preventivi/{{ $preventivo->id }}/aggiungi-riga-prodotto">
        @csrf

        <p>Descrizione:<br><input type="text" name="descrizione"></p>
        <p>Quantità:<br><input type="number" name="quantita" step="0.01" value="1"></p>
        <p>Prezzo listino:<br><input type="number" name="prezzo_listino" step="0.01"></p>
        <p>Costo netto:<br><input type="number" name="costo_netto" step="0.01"></p>

        <p>Sconto 1:<br><input type="number" name="sconto_fornitore_1"></p>
        <p>Sconto 2:<br><input type="number" name="sconto_fornitore_2"></p>
        <p>Sconto 3:<br><input type="number" name="sconto_fornitore_3"></p>

        <p>Ricarico %:<br><input type="number" name="ricarico_percentuale"></p>

        <input type="hidden" name="modalita_calcolo" value="da_listino">

        <button type="submit">Salva</button>
    </form>
</details>

<br>

<table border="1" cellpadding="5">
<tr>
    <th>Prodotto</th>
    <th>Prezzo</th>
    <th>Totale</th>
    <th>Dettagli</th>
    <th>Azioni</th>
</tr>

@foreach($preventivo->righeProdotti as $riga)

<tr>
    <td>
        <strong>{{ $riga->descrizione }}</strong><br>
        Qta: {{ $riga->quantita }}
    </td>

    <td>
        {{ number_format($riga->prezzo_cliente_unitario, 2, ',', '.') }} €
    </td>

    <td>
        {{ number_format($riga->totale_cliente, 2, ',', '.') }} €
    </td>

    <td>
        <details>
            <summary>Apri dettagli</summary>

            <p><strong>Listino:</strong> {{ $riga->prezzo_listino }}</p>
            <p><strong>Sconto 1:</strong> {{ $riga->sconto_fornitore_1 }}%</p>
            <p><strong>Sconto 2:</strong> {{ $riga->sconto_fornitore_2 }}%</p>
            <p><strong>Sconto 3:</strong> {{ $riga->sconto_fornitore_3 }}%</p>
            <p><strong>Costo netto:</strong> {{ $riga->costo_netto }}</p>
            <p><strong>Ricarico:</strong> {{ $riga->ricarico_percentuale }}%</p>
            <p><strong>Sconto cliente:</strong> {{ $riga->sconto_cliente_percentuale }}%</p>
        </details>
    </td>

    <td>
        <form action="/righe-preventivo-prodotti/{{ $riga->id }}" method="POST">
            @csrf
            @method('DELETE')
            <button>Elimina</button>
        </form>
    </td>
</tr>

<!-- SERVIZI -->
<tr>
<td colspan="5" style="background:#f5f5f5; padding:10px;">

    <details {{ $riga->servizi->count() > 0 ? 'open' : '' }}>
        <summary>
            <strong>Servizi ({{ $riga->servizi->count() }})</strong>
        </summary>

        @if($riga->servizi->count() > 0)
            <ul>
                @foreach($riga->servizi as $servizio)
                    <li>
                        {{ $servizio->tipo_servizio }} -
                        {{ number_format($servizio->prezzo_cliente, 2, ',', '.') }} €

                        <form action="/servizi-riga/{{ $servizio->id }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button>X</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Nessun servizio</p>
        @endif

        <details>
            <summary><strong>+ Aggiungi servizio</strong></summary>

            <form method="POST" action="/righe-prodotti/{{ $riga->id }}/servizi">
                @csrf

                <select name="tipo_servizio">
                    <option value="posa">Posa</option>
                    <option value="trasporto">Trasporto</option>
                    <option value="smaltimento">Smaltimento</option>
                </select>

                <input type="number" name="costo_brc" step="0.01" placeholder="Costo">
                <input type="number" name="ricarico_percentuale" step="0.01" placeholder="Ricarico %">

                <button type="submit">OK</button>
            </form>
        </details>

    </details>

</td>
</tr>

@endforeach

</table>

<br>

<a href="/preventivi">← Torna alla lista</a>