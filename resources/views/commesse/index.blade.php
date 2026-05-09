@include('partials.menu')

<div class="container">

    <h1>Lista Commesse</h1>

    <form method="GET" action="/commesse" style="margin-bottom:15px;">
        <input type="text" name="q" placeholder="Cerca cliente..." value="{{ request('q') }}">

        <button type="submit" class="btn btn-azione">
            Cerca
        </button>

        <a href="/commesse" class="btn btn-azione">
            Reset
        </a>
    </form>

    <a href="/commesse/create" class="btn btn-azione">
        + Nuova Commessa
    </a>

    @if(session('success'))
        <p style="color:green;">
            {{ session('success') }}
        </p>
    @endif

    @if(session('error'))
        <p style="color:red;">
            {{ session('error') }}
        </p>
    @endif

    <table class="tabella-lista">

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

                <td>
                    {{ $commessa->citta_lavoro }}
                </td>

                <td>
                    {{ $commessa->indirizzo_lavoro }}
                </td>

                <td>
                    {{ $commessa->piano_posa ?? '-' }}
                </td>

                <td>
                    @if($commessa->autoscala)
                        <span style="color:red;">Sì</span>
                    @else
                        No
                    @endif
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/commesse/{{ $commessa->id }}/edit" class="btn btn-azione">
                            Modifica
                        </a>

                        <form action="/commesse/{{ $commessa->id }}" method="POST" class="form-elimina">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-elimina"
                                onclick="return confirm('Sei sicuro di voler eliminare questa commessa?')"
                            >
                                🗑️
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @endforeach

    </table>

</div>