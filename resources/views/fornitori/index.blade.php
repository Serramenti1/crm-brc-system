@include('partials.menu')

<div class="container">

    <h1>Fornitori</h1>

    <form method="GET" action="/fornitori" style="margin-bottom:15px;">

        <input
            type="text"
            name="q"
            placeholder="Cerca fornitore..."
            value="{{ request('q') }}"
        >

        <button type="submit" class="btn btn-azione">
            Cerca
        </button>

        <a href="/fornitori" class="btn btn-azione">
            Reset
        </a>

    </form>

    <a href="/fornitori/create" class="btn btn-azione">
        + Nuovo Fornitore
    </a>

    <table class="tabella-lista">

        <tr>
            <th>Nome</th>
            <th>Referente</th>
            <th>Telefono</th>
            <th>Email</th>
            <th>Azioni</th>
        </tr>

        @foreach($fornitori as $f)

            <tr>

                <td>
                    {{ $f->ragione_sociale }}
                </td>

                <td>
                    {{ $f->referente }}
                </td>

                <td>
                    {{ $f->telefono }}
                </td>

                <td>
                    {{ $f->email }}
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/fornitori/{{ $f->id }}" class="btn btn-azione">
                            Visualizza
                        </a>

                        <a href="/fornitori/{{ $f->id }}/edit" class="btn btn-azione">
                            Modifica
                        </a>

                        <form method="POST"
                              action="/fornitori/{{ $f->id }}"
                              class="form-elimina">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-elimina"
                                onclick="return confirm('Eliminare questo fornitore?')"
                            >
                                🗑️
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @endforeach

    </table>

    <div style="margin-top:20px;">

        <a href="/impostazioni" class="btn btn-azione">
            ← Torna alle impostazioni
        </a>

    </div>

</div>