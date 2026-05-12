@include('partials.menu')

<div class="container">

    <h1>Lista Clienti</h1>

    <form method="GET" action="/clienti" style="margin-bottom:15px;">
        <input type="text" name="q" placeholder="Cerca cliente..." value="{{ request('q') }}">

        <button type="submit" class="btn btn-azione">
            Cerca
        </button>

        <a href="/clienti" class="btn btn-azione">
            Reset
        </a>
    </form>

    <a href="/clienti/create" class="btn btn-azione">
        + Nuovo Cliente
    </a>

    <table class="tabella-lista">

        <tr>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Indirizzo</th>
            <th>Contatti</th>
            <th>Azioni</th>
        </tr>

        @foreach($clienti as $cliente)

            <tr>

                <td>
                    {{ $cliente->nome }}
                </td>

                <td>
                    {{ $cliente->cognome }}
                </td>

                <td>
                    {{ $cliente->indirizzo }}<br>

                    {{ $cliente->cap }}
                    {{ $cliente->citta }}
                    ({{ $cliente->provincia }})
                </td>

                <td>
                    {{ $cliente->telefono }}<br>
                    {{ $cliente->email }}
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/clienti/{{ $cliente->id }}" class="btn btn-azione">
                            Visualizza
                        </a>

                        <a href="/clienti/{{ $cliente->id }}/edit" class="btn btn-azione">
                            Modifica
                        </a>

                       @if($cliente->commesse->count() > 0)

    <button
        type="button"
        class="btn btn-elimina"
        style="background:#9ca3af; cursor:not-allowed;"
        title="Cliente con commesse collegate"
    >
        🗑️
    </button>

@else

    <form action="/clienti/{{ $cliente->id }}" method="POST" class="form-elimina">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="btn btn-elimina"
            onclick="return confirm('Eliminare questo cliente?')"
        >
            🗑️
        </button>

    </form>

@endif

                    </div>

                </td>

            </tr>

        @endforeach

    </table>

</div>