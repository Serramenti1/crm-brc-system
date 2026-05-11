@include('partials.menu')

<div class="container">

    <h1>Prodotti Fornitore</h1>

    <form method="GET"
          action="/prodotti-fornitore"
          style="margin-bottom:15px;">

        <input type="text"
               name="q"
               placeholder="Cerca prodotto o fornitore..."
               value="{{ request('q') }}">

        <button type="submit" class="btn btn-azione">
            Cerca
        </button>

        <a href="/prodotti-fornitore" class="btn btn-azione">
            Reset
        </a>

    </form>

    <a href="/prodotti-fornitore/create" class="btn btn-azione">
        + Nuovo Prodotto
    </a>

    <table class="tabella-lista">

        <tr>
            <th>Fornitore</th>
            <th>Descrizione</th>
            <th>Listino</th>
            <th>Sconti</th>
            <th>Bene significativo</th>
            <th>Azioni</th>
        </tr>

        @foreach($prodotti as $p)

            <tr>

                <td>
                    {{ $p->fornitore->ragione_sociale ?? '' }}
                </td>

                <td>
                    {{ $p->descrizione }}
                </td>

                <td>
                    {{ number_format($p->prezzo_listino, 2, ',', '.') }} €
                </td>

                <td>
                    {{ $p->sconto_1 }}%
                    /
                    {{ $p->sconto_2 }}%
                    /
                    {{ $p->sconto_3 }}%
                </td>

                <td>
                    {{ $p->bene_significativo ? 'SI' : 'NO' }}
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/prodotti-fornitore/{{ $p->id }}"
                           class="btn btn-azione">
                            Visualizza
                        </a>

                        <a href="/prodotti-fornitore/{{ $p->id }}/edit"
                           class="btn btn-azione">
                            Modifica
                        </a>

                        <form method="POST"
                              action="/prodotti-fornitore/{{ $p->id }}"
                              class="form-elimina">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-elimina"
                                    onclick="return confirm('Eliminare questo prodotto?')">
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