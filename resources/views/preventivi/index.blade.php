@include('partials.menu')

<h1>Lista Preventivi</h1>

<a href="/preventivi/create">+ Nuovo Preventivo</a>

<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Commessa</th>
        <th>Numero</th>
        <th>Versione</th>
        <th>Stato</th>
        <th>Totale Cliente</th>
        <th>Sconto Medio</th>
        <th>Azioni</th>
    </tr>

    @foreach($preventivi as $preventivo)
    <tr>
        <td>{{ $preventivo->id }}</td>
        <td>
            {{ $preventivo->commessa && $preventivo->commessa->cliente ? $preventivo->commessa->cliente->nome . ' ' . $preventivo->commessa->cliente->cognome : '' }}
        </td>
        <td>{{ $preventivo->commessa ? $preventivo->commessa->titolo : '' }}</td>
        <td>{{ $preventivo->numero }}</td>
        <td>{{ $preventivo->versione }}</td>
        <td>{{ $preventivo->stato }}</td>
        <td>{{ $preventivo->totale_cliente_finale }}</td>
        <td>{{ $preventivo->sconto_medio_cliente }}</td>
        <td>
            <a href="/preventivi/{{ $preventivo->id }}">Apri</a>
        </td>
    </tr>
    @endforeach
</table>