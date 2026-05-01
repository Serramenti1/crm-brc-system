@include('partials.menu')

<h1>Lista Clienti</h1>

<form method="GET" action="/clienti" style="margin-bottom:15px;">
    <input type="text" name="q" placeholder="Cerca cliente..." value="{{ request('q') }}">
    <button type="submit">Cerca</button>
    <a href="/clienti">Reset</a>
</form>

<a href="/clienti/create">+ Nuovo Cliente</a>

<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Indirizzo</th>
        <th>Contatti</th>
        <th>Azioni</th>
    </tr>

    @foreach($clienti as $cliente)
    <tr>
        <td>{{ $cliente->nome }}</td>
        <td>{{ $cliente->cognome }}</td>
        <td>
            {{ $cliente->indirizzo }}<br>
            {{ $cliente->cap }} {{ $cliente->citta }} ({{ $cliente->provincia }})
        </td>
        <td>
            {{ $cliente->telefono }}<br>
            {{ $cliente->email }}
        </td>
        <td>
            <a href="/clienti/{{ $cliente->id }}">Visualizza</a> |

            <a href="/clienti/{{ $cliente->id }}/edit">Modifica</a>

            <form action="/clienti/{{ $cliente->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Eliminare questo cliente?')">
                    Elimina
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>