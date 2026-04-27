@include('partials.menu')

<h1>Lista Preventivi</h1>

<a href="/preventivi/create">+ Nuovo Preventivo</a>

<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Numero</th>
        <th>Descrizione</th>
        <th>Cliente</th>
        <th>Commessa</th>
        <th>Totale Cliente</th>
        <th>Sconto Medio</th>
        <th>Utile</th>
        <th>Azioni</th>
    </tr>

    @foreach($preventivi as $preventivo)
    <tr>
        <td>{{ $preventivo->id }}</td>

        <td>
            <strong>{{ $preventivo->numero }}</strong>
        </td>

        <td>{{ $preventivo->descrizione }}</td>

        <td>
            {{ $preventivo->commessa && $preventivo->commessa->cliente 
                ? $preventivo->commessa->cliente->nome . ' ' . $preventivo->commessa->cliente->cognome 
                : '' }}
        </td>

        <td>
            {{ $preventivo->commessa ? $preventivo->commessa->titolo : '' }}
        </td>

        <td>
            {{ number_format($preventivo->totale_cliente_finale, 2, ',', '.') }} €
        </td>

        <td>
            {{ number_format($preventivo->sconto_medio_cliente, 2, ',', '.') }} %
        </td>

        <td>
            {{ number_format($preventivo->utile_totale, 2, ',', '.') }} €
        </td>

        <td>
            <a href="/preventivi/{{ $preventivo->id }}">Apri</a>
        </td>
    </tr>
    @endforeach
</table>