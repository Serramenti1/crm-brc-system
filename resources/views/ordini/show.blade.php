@include('partials.menu')

<h1>Dettaglio Ordine</h1>

<p><strong>Numero ordine:</strong> {{ $ordine->numero }}</p>
<p><strong>Preventivo origine:</strong> {{ $ordine->preventivo->numero ?? '' }}</p>

<p>
    <strong>Cliente:</strong>
    {{ optional(optional($ordine->preventivo->commessa)->cliente)->nome }}
    {{ optional(optional($ordine->preventivo->commessa)->cliente)->cognome }}
</p>

<p>
    <strong>Costo a noi:</strong>
    {{ number_format($ordine->imponibile, 2, ',', '.') }} €
</p>

<p>
    <strong>Stato:</strong>
    @if($ordine->stato == 'completo')
        <span style="color:green;">Completo</span>
    @else
        <span style="color:orange;">In lavorazione</span>
    @endif
</p>

<hr>

<h2>Righe Ordine</h2>

<table border="1" cellpadding="5" width="100%">
    <tr>
        <th>Prodotto</th>
        <th>Fornitore</th>
        <th>Quantità</th>
        <th>Costo a noi</th>
        <th>Stati</th>
        <th>PDF</th>
        <th>Salva</th>
    </tr>

    @foreach($ordine->righe as $riga)
    <tr>
        <form method="POST" action="/righe-ordine/{{ $riga->id }}/aggiorna" enctype="multipart/form-data">
        @csrf

        <td>{{ $riga->descrizione }}</td>

        <td>{{ $riga->fornitore->ragione_sociale ?? '' }}</td>

        <td>{{ $riga->quantita }}</td>

        <td>{{ number_format($riga->imponibile, 2, ',', '.') }} €</td>

        <td>
            <label>
                <input type="checkbox" name="inviato" value="1" {{ $riga->inviato ? 'checked' : '' }}>
                Inviato
            </label>

            <br>

            <label>
                <input type="checkbox" name="co_ricevuta" value="1" {{ $riga->co_ricevuta ? 'checked' : '' }}>
                CO ricevuta
            </label>

            <br>

            <label>
                <input type="checkbox" name="in_produzione" value="1" {{ $riga->in_produzione ? 'checked' : '' }}>
                In produzione
            </label>
        </td>

        <td>
            @if($riga->pdf_path)
                <a href="{{ asset('storage/' . $riga->pdf_path) }}" target="_blank">Apri PDF</a><br>
            @endif

            <input type="file" name="pdf" accept="application/pdf">
        </td>

        <td>
            <button type="submit">Salva</button>
        </td>

        </form>
    </tr>
    @endforeach
</table>

<br>

@if($ordine->stato == 'completo')
    <a href="/ordini-completi">← Torna agli ordini completi</a>
@else
    <a href="/ordini">← Torna agli ordini in lavorazione</a>
@endif