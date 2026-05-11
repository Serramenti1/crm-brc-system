@include('partials.menu')

<div class="container">

    <h1>Dettaglio Fornitore</h1>

    <div style="margin-bottom:20px;">

        <a href="/fornitori" class="btn btn-azione">
            ← Torna ai fornitori
        </a>

    </div>

    <table class="tabella-dettaglio">

        <tr>
            <th colspan="2">
                Dati fornitore
            </th>
        </tr>

        <tr>
            <td><strong>Ragione sociale</strong></td>
            <td>{{ $fornitore->ragione_sociale }}</td>
        </tr>

        <tr>
            <td><strong>Referente</strong></td>
            <td>{{ $fornitore->referente }}</td>
        </tr>

        <tr>
            <td><strong>Telefono</strong></td>
            <td>{{ $fornitore->telefono }}</td>
        </tr>

        <tr>
            <td><strong>Email</strong></td>
            <td>{{ $fornitore->email }}</td>
        </tr>

        <tr>
            <td><strong>Sconto 1</strong></td>
            <td>{{ $fornitore->sconto_standard_1 }}%</td>
        </tr>

        <tr>
            <td><strong>Sconto 2</strong></td>
            <td>{{ $fornitore->sconto_standard_2 }}%</td>
        </tr>

        <tr>
            <td><strong>Sconto 3</strong></td>
            <td>{{ $fornitore->sconto_standard_3 }}%</td>
        </tr>

        <tr>
            <td><strong>Note</strong></td>
            <td>
                {!! nl2br(e($fornitore->note)) !!}
            </td>
        </tr>

    </table>

    <br>

    <h2>Prodotti collegati</h2>

    <table class="tabella-lista">

        <tr>
            <th>Descrizione</th>
            <th>Prezzo listino</th>
            <th>Bene significativo</th>
        </tr>

        @forelse($fornitore->prodotti as $prodotto)

            <tr>

                <td>
                    {{ $prodotto->descrizione }}
                </td>

                <td>
                    {{ number_format($prodotto->prezzo_listino, 2, ',', '.') }} €
                </td>

                <td>
                    {{ $prodotto->bene_significativo ? 'SI' : 'NO' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="3">
                    Nessun prodotto collegato.
                </td>
            </tr>

        @endforelse

    </table>

</div>