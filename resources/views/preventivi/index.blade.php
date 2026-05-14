@include('partials.menu')

<div class="container">

    <h1>Lista Preventivi</h1>

    <form method="GET" action="/preventivi" style="margin-bottom:15px;">

        <input
            type="text"
            name="cliente"
            placeholder="Cerca cliente..."
            value="{{ request('cliente') }}"
        >

        <button type="submit" class="btn btn-azione">
            Cerca
        </button>

        <a href="/preventivi" class="btn btn-azione">
            Reset
        </a>

    </form>

    <a href="/preventivi/create" class="btn btn-azione">
        + Nuovo Preventivo
    </a>

    <table class="tabella-lista">

        <tr>
            <th>Numero</th>
            <th>Cliente</th>
            <th>Commessa</th>
            <th>Tipo intervento</th>
            <th>Totale Cliente Ivato</th>
            <th>Azioni</th>
        </tr>

        @foreach($preventivi as $preventivo)

            <tr>

                <td>
                    <strong>{{ $preventivo->numero }}</strong>
                </td>

                <td>
                    {{ $preventivo->commessa && $preventivo->commessa->cliente
                        ? $preventivo->commessa->cliente->nome . ' ' . $preventivo->commessa->cliente->cognome
                        : '' }}
                </td>

                <td>
                    @if($preventivo->commessa)

                        {{ $preventivo->commessa->titolo }}

                        <br>

                        <small>
                            {{ $preventivo->commessa->indirizzo_lavoro }}

                            @if($preventivo->commessa->citta_lavoro)
                                - {{ $preventivo->commessa->citta_lavoro }}
                            @endif
                        </small>

                    @endif
                </td>
                <td>
                     {{ $preventivo->commessa?->tipoIntervento?->nome }}
                </td>

                <td>
                    {{ number_format($preventivo->totale_ivato_lista ?? $preventivo->totale_cliente_finale, 2, ',', '.') }} €
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/preventivi/{{ $preventivo->id }}" class="btn btn-azione">
                            Modifica
                        </a>

                        <a href="/preventivi/{{ $preventivo->id }}/visualizza" class="btn btn-azione">
                            Visualizza
                        </a>

                        @if($preventivo->ordine)

                            <a href="/ordini/{{ $preventivo->ordine->id }}" class="btn btn-azione">
                                Apri ordine
                            </a>

                        @else

                            <form
                                action="/preventivi/{{ $preventivo->id }}/crea-ordine"
                                method="POST"
                                class="form-elimina"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-azione"
                                    onclick="return confirm('Creare ordine da questo preventivo?')"
                                >
                                    Crea ordine
                                </button>

                            </form>

                        @endif

                        @if($preventivo->ordine)

                            <button
                                type="button"
                                class="btn btn-elimina btn-disabilitato"
                                title="Preventivo non eliminabile: ordine collegato"
                            >
                                🗑️
                            </button>

                        @else

                            <form
                                action="/preventivi/{{ $preventivo->id }}"
                                method="POST"
                                class="form-elimina"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-elimina"
                                    onclick="return confirm('Eliminare questo preventivo?')"
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

    <div style="margin-top:20px; display:flex; justify-content:center;">

    {{ $preventivi->links('pagination::bootstrap-4') }}

</div>

</div>