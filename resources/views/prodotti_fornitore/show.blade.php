@include('partials.menu')

<div class="container">

    <h1>Dettaglio Prodotto Fornitore</h1>

    <div style="margin-bottom:20px;">

        <a href="/prodotti-fornitore" class="btn btn-azione">
            ← Torna ai prodotti fornitore
        </a>

        <a href="/prodotti-fornitore/{{ $prodotto->id }}/edit" class="btn btn-azione">
            Modifica
        </a>

    </div>

    <table class="tabella-dettaglio">

        <tr>
            <th colspan="2">
                {{ $prodotto->descrizione }}
            </th>
        </tr>

        <tr>
            <td><strong>Fornitore</strong></td>
            <td>{{ $prodotto->fornitore?->ragione_sociale }}</td>
        </tr>

        <tr>
            <td><strong>Descrizione</strong></td>
            <td>{{ $prodotto->descrizione }}</td>
        </tr>

        <tr>
            <td><strong>Prezzo listino</strong></td>
            <td>{{ number_format($prodotto->prezzo_listino, 2, ',', '.') }} €</td>
        </tr>

        <tr>
            <td><strong>Sconto 1</strong></td>
            <td>{{ $prodotto->sconto_1 }}%</td>
        </tr>

        <tr>
            <td><strong>Sconto 2</strong></td>
            <td>{{ $prodotto->sconto_2 }}%</td>
        </tr>

        <tr>
            <td><strong>Sconto 3</strong></td>
            <td>{{ $prodotto->sconto_3 }}%</td>
        </tr>

        <tr>
            <td><strong>Bene significativo</strong></td>
            <td>{{ $prodotto->bene_significativo ? 'SI' : 'NO' }}</td>
        </tr>

    </table>

</div>