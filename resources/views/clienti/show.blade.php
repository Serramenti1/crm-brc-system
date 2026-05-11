@include('partials.menu')

<div class="container">

    <h1>Dettaglio Cliente</h1>

    <div style="margin-bottom:20px;">

        <a href="/clienti" class="btn btn-azione">
            ← Torna ai clienti
        </a>

        <a href="/clienti/{{ $cliente->id }}/edit" class="btn btn-azione">
            Modifica
        </a>

    </div>

    <table class="tabella-dettaglio">

        <tr>
            <th colspan="2">
                {{ $cliente->nome }} {{ $cliente->cognome }}
            </th>
        </tr>

        <tr>
            <td><strong>Indirizzo</strong></td>
            <td>{{ $cliente->indirizzo }}</td>
        </tr>

        <tr>
            <td><strong>Città</strong></td>
            <td>
                {{ $cliente->cap }}
                {{ $cliente->citta }}
                ({{ $cliente->provincia }})
            </td>
        </tr>

        <tr>
            <td><strong>Telefono</strong></td>
            <td>{{ $cliente->telefono }}</td>
        </tr>

        <tr>
            <td><strong>Email</strong></td>
            <td>{{ $cliente->email }}</td>
        </tr>

        <tr>
            <td><strong>Codice fiscale</strong></td>
            <td>{{ $cliente->codice_fiscale }}</td>
        </tr>

        <tr>
            <td><strong>Partita IVA</strong></td>
            <td>{{ $cliente->partita_iva }}</td>
        </tr>

        <tr>
            <td><strong>Note</strong></td>
            <td>{!! nl2br(e($cliente->note)) !!}</td>
        </tr>

    </table>

    <h2>Commesse collegate</h2>

    <table class="tabella-lista">

        <tr>
            <th>Titolo</th>
            <th>Indirizzo</th>
            <th>Tipo intervento</th>
            <th>Azioni</th>
        </tr>

        @forelse($cliente->commesse as $commessa)

            <tr>

                <td>
                    {{ $commessa->titolo }}
                </td>

                <td>
                    {{ $commessa->indirizzo_lavoro }}
                </td>

                <td>
                    {{ $commessa->tipoIntervento?->nome }}
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/commesse/{{ $commessa->id }}" class="btn btn-azione">
                            Apri commessa
                        </a>

                    </div>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4">
                    Nessuna commessa collegata.
                </td>
            </tr>

        @endforelse

    </table>

</div>