@include('partials.menu')

<h1>Lista Commesse</h1>

<form method="GET" action="/commesse" style="margin-bottom:15px;">
    <input type="text" name="q" placeholder="Cerca cliente..." value="{{ request('q') }}">
    <button type="submit">Cerca</button>
    <a href="/commesse">Reset</a>
</form>

<a href="/commesse/create">+ Nuova Commessa</a>

<br><br>

<table border="1" cellpadding="5" width="100%">
    <tr>
        <th>Cliente</th>
        <th>Città intervento</th>
        <th>Indirizzo intervento</th>
        <th>Piano posa</th>
        <th>Autoscala</th>
        <th>Azioni</th>
    </tr>

    @foreach($commesse as $commessa)
    <tr>

        <td>
            {{ $commessa->cliente ? $commessa->cliente->nome . ' ' . $commessa->cliente->cognome : '' }}
        </td>

        <td>{{ $commessa->citta_lavoro }}</td>

        <td>{{ $commessa->indirizzo_lavoro }}</td>

        <td>{{ $commessa->piano_posa ?? '-' }}</td>

        <td>
            @if($commessa->autoscala)
                <span style="color:red;">Sì</span>
            @else
                No
            @endif
        </td>

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