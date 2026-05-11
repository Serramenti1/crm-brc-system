@include('partials.menu')

<div class="container">

    <h1>Lista Commesse</h1>

    <form method="GET" action="/commesse" style="margin-bottom:15px;">

        <input
            type="text"
            name="q"
            placeholder="Cerca commessa..."
            value="{{ request('q') }}"
        >

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

    <table class="tabella-lista">

        <tr>
            <th>Cliente</th>
            <th>Intervento</th>
            <th>Tipo lavoro</th>
            <th>Detrazione</th>
            <th>Stato</th>
            <th>Azioni</th>
        </tr>

        @foreach($commesse as $commessa)

            <tr>

                <td>

                    @if($commessa->cliente)

                        {{ $commessa->cliente->nome }}
                        {{ $commessa->cliente->cognome }}

                    @endif

                </td>

                <td>

                    {{ $commessa->indirizzo_lavoro }}<br>

                    {{ $commessa->cap_lavoro }}
                    {{ $commessa->citta_lavoro }}
                    ({{ $commessa->provincia_lavoro }})

                </td>

                <td>

                    @if($commessa->tipoIntervento)

                        {{ $commessa->tipoIntervento->nome }}

                    @endif

                </td>

                <td>

                    {{ $commessa->tipo_detrazione }}

                    @if($commessa->percentuale_detrazione)

                        <br>

                        {{ number_format($commessa->percentuale_detrazione, 0) }}%

                    @endif

                </td>

                <td>

                    {{ ucfirst($commessa->stato) }}

                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/commesse/{{ $commessa->id }}" class="btn btn-azione">
                            Visualizza
                        </a>

                        <a href="/commesse/{{ $commessa->id }}/edit" class="btn btn-azione">
                            Modifica
                        </a>

                        <form
                            action="/commesse/{{ $commessa->id }}"
                            method="POST"
                            class="form-elimina"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-elimina"
                                onclick="return confirm('Eliminare questa commessa?')"
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