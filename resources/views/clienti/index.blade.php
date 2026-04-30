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
        <th>ID</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Email</th>
        <th>Telefono</th>
        <th>Indirizzo</th>
        <th>Città</th>
        <th>CAP</th>
        <th>Provincia</th>
        <th>Codice Fiscale</th>
        <th>Partita IVA</th>
        <th>Azioni</th>
    </tr>

    @foreach($clienti as $cliente)
    <tr>
        <td>{{ $cliente->id }}</td>
        <td>{{ $cliente->nome }}</td>
        <td>{{ $cliente->cognome }}</td>
        <td>{{ $cliente->email }}</td>
        <td>{{ $cliente->telefono }}</td>
        <td>{{ $cliente->indirizzo }}</td>
        <td>{{ $cliente->citta }}</td>
        <td>{{ $cliente->cap }}</td>
        <td>{{ $cliente->provincia }}</td>
        <td>{{ $cliente->codice_fiscale }}</td>
        <td>{{ $cliente->partita_iva }}</td>
        <td>
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