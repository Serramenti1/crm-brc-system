@include('partials.menu')

@php
    $titoliStato = [
        'preparazione_contratto' => 'Preparazione contratto',
        'in_lavorazione' => 'Ordini in lavorazione',
        'completo_attesa_merce' => 'Ordini in attesa merce',
        'attesa_saldo_merce' => 'Ordini in attesa saldo merce',
        'programmare_posa' => 'Ordini da programmare posa',
        'concluso' => 'Ordini conclusi',
        'archiviato' => 'Ordini archiviati',
    ];

    $statoCorrente = $stato ?? 'preparazione_contratto';
@endphp

<div class="container">

    <h1>{{ $titoliStato[$statoCorrente] ?? 'Lista Ordini' }}</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <table class="tabella-lista">

        <tr>
            <th>Numero</th>
            <th>Cliente</th>
            <th>Commessa</th>
            <th>Tipo intervento</th>
            <th>Totale Cliente Ivato</th>
            <th>Stato</th>
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
                    @if($ordine->stato == 'preparazione_contratto')
                        Preparazione contratto
                    @elseif($ordine->stato == 'in_lavorazione')
                        In lavorazione
                    @elseif($ordine->stato == 'completo_attesa_merce')
                        Attesa merce
                    @elseif($ordine->stato == 'attesa_saldo_merce')
                        Attesa saldo merce
                    @elseif($ordine->stato == 'programmare_posa')
                        Programmare posa
                    @elseif($ordine->stato == 'concluso')
                        Concluso
                    @elseif($ordine->stato == 'archiviato')
                        Archiviato
                    @else
                        {{ $ordine->stato }}
                    @endif

                    @if($ordine->stato == 'preparazione_contratto')
                        <br>
                        <small>
                            Rilievo:
                            {{ $ordine->rilievo_effettuato ? 'Sì' : 'No' }}
                            |
                            Contratto:
                            {{ $ordine->contratto_firmato ? 'Sì' : 'No' }}
                            |
                            Acconto:
                            {{ $ordine->acconto_versato ? 'Sì' : 'No' }}
                        </small>
                    @endif
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/ordini/{{ $ordine->id }}" class="btn btn-azione">
                            Apri
                        </a>

                        <form
                            action="/ordini/{{ $ordine->id }}"
                            method="POST"
                            class="form-elimina"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-elimina"
                                onclick="return confirm('Eliminare questo ordine?')"
                            >
                                🗑️
                            </button>
                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="7">
                    Nessun ordine in questa sezione.
                </td>
            </tr>

        @endforelse

    </table>

</div>