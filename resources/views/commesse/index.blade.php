@include('partials.menu')

<h1>Lista Commesse</h1>

<form method="GET" action="/commesse" style="margin-bottom:15px;">
    <input type="text" name="q" placeholder="Cerca commessa o cliente..." value="{{ request('q') }}">
    <button type="submit">Cerca</button>
    <a href="/commesse">Reset</a>
</form>

<a href="/commesse/create">+ Nuova Commessa</a>

<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Indirizzo cantiere</th>
        <th>Tipologia abitazione</th>
        <th>Tipo lavoro</th>
        <th>Detrazione</th>
        <th>Stato</th>
        <th>Azioni</th>
    </tr>

    @foreach($commesse as $commessa)
    <tr>
        <td>{{ $commessa->id }}</td>

        <td>
            {{ $commessa->cliente ? $commessa->cliente->nome . ' ' . $commessa->cliente->cognome : '' }}
        </td>

        <td>{{ $commessa->indirizzo_lavoro }}</td>

        <td>{{ $commessa->tipologia_abitazione }}</td>
        <td>{{ $commessa->tipo_lavoro }}</td>
        <td>{{ $commessa->tipo_detrazione }}</td>
        <td>{{ $commessa->stato }}</td>

        <td>
            <a href="/commesse/{{ $commessa->id }}/edit">Modifica</a>

            <form action="/commesse/{{ $commessa->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Sei sicuro di voler eliminare questa commessa?')">
                    Elimina
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>