@include('partials.menu')

<div class="container">

    <h1>Archiviati</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <form method="GET" action="/ordini/archiviati" style="margin-bottom:15px;">
        <input type="text"
               name="q"
               placeholder="Cerca per cliente, commessa, numero ordine..."
               value="{{ request('q') }}">

        <button type="submit" class="btn btn-azione">
            Cerca
        </button>

        <a href="/ordini/archiviati" class="btn btn-azione">
            Reset
        </a>
    </form>

    <table class="tabella-lista">

        <tr>
            <th>Numero</th>
            <th>Cliente</th>
            <th>Commessa</th>
            <th>Tipo intervento</th>
            <th>Totale cliente ivato</th>
            <th>ENEA</th>
            <th>Azioni</th>
        </tr>

        @forelse($ordini as $ordine)

            <tr>

                <td>
                    <strong>{{ $ordine->numero }}</strong>
                </td>

                <td>
                    {{ $ordine->commessa && $ordine->commessa->cliente
                        ? $ordine->commessa->cliente->nome . ' ' . $ordine->commessa->cliente->cognome
                        : '' }}
                </td>

                <td>
                    @if($ordine->commessa)
                        {{ $ordine->commessa->titolo }}
                        <br>
                        <small>
                            {{ $ordine->commessa->indirizzo_lavoro }}
                            @if($ordine->commessa->citta_lavoro)
                                - {{ $ordine->commessa->citta_lavoro }}
                            @endif
                        </small>
                    @endif
                </td>

                <td>
                    {{ $ordine->commessa?->tipoIntervento?->nome }}
                </td>

                <td>
                    {{ number_format($ordine->totale_con_iva ?? 0, 2, ',', '.') }} €
                </td>

                <td>
                    @if($ordine->commessa && $ordine->commessa->pratica_enea)
                        @if($ordine->archivio_pratica_enea_inviata)
                            <span style="color:green; font-weight:bold;">✓ Inviata</span>
                        @else
                            <span style="color:red; font-weight:bold;">⚠ Da inviare</span>
                        @endif
                    @else
                        -
                    @endif
                </td>

                <td class="azioni">
                    <div class="azioni-bottoni">
                        <a href="/ordini/{{ $ordine->id }}" class="btn btn-azione">
                            Apri
                        </a>
                        <a href="/ordini/{{ $ordine->id }}/visualizza" class="btn btn-azione">
                            Visualizza
                        </a>
                    </div>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="7">
                    Nessun ordine archiviato.
                </td>
            </tr>

        @endforelse

    </table>

    <div style="margin-top:20px; display:flex; justify-content:center;">
        {{ $ordini->links('pagination::bootstrap-4') }}
    </div>

</div>