@include('partials.menu')

<h1>Righe Prodotti Preventivo</h1>

<a href="/righe-preventivo-prodotti/create">+ Nuova Riga Prodotto</a>

<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Modalità</th>
        <th>Preventivo</th>
        <th>Fornitore</th>
        <th>Descrizione</th>
        <th>Qta</th>
        <th>Listino</th>
        <th>Costo Netto</th>
        <th>Ricarico %</th>
        <th>Prezzo Cliente</th>
        <th>Sconto Cliente %</th>
        <th>Totale Cliente</th>
    </tr>

    @foreach($righe as $riga)
    <tr>
        <td>{{ $riga->id }}</td>
        <td>{{ $riga->modalita_calcolo }}</td>
        <td>{{ $riga->preventivo_id }}</td>
        <td>{{ $riga->fornitore ? $riga->fornitore->ragione_sociale : '' }}</td>
        <td>{{ $riga->descrizione }}</td>
        <td>{{ $riga->quantita }}</td>
        <td>{{ $riga->prezzo_listino }}</td>
        <td>{{ $riga->costo_netto }}</td>
        <td>{{ $riga->ricarico_percentuale }}</td>
        <td>{{ $riga->prezzo_cliente_unitario }}</td>
        <td>{{ $riga->sconto_cliente_percentuale }}</td>
        <td>{{ $riga->totale_cliente }}</td>
    </tr>
    @endforeach
</table>