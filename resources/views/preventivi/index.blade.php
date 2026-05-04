@include('partials.menu')

<h1>Lista Preventivi</h1>

<a href="/preventivi/create">+ Nuovo Preventivo</a>

<br><br>

<!-- 🔍 RICERCA -->
<form method="GET" action="/preventivi">
    <input type="text" name="cliente" placeholder="Cerca cliente..." value="{{ request('cliente') }}">
    <button type="submit">Cerca</button>
    <a href="/preventivi">Reset</a>
</form>

<br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Numero</th>
        <th>Cliente</th>
        <th>Descrizione</th>
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

        <td>
            {{ $preventivo->commessa && $preventivo->commessa->cliente 
                ? $preventivo->commessa->cliente->nome . ' ' . $preventivo->commessa->cliente->cognome 
                : '' }}
        </td>
        
        <td>{{ $preventivo->descrizione }}</td>

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

            @if($preventivo->ordine)
                | <a href="/ordini/{{ $preventivo->ordine->id }}">Ordine</a>
            @else
                <form action="/preventivi/{{ $preventivo->id }}/crea-ordine" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" onclick="return confirm('Creare ordine da questo preventivo?')">
                        Crea ordine
                    </button>
                </form>
            @endif

            <form action="/preventivi/{{ $preventivo->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Eliminare questo preventivo?')">
                    Elimina
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>