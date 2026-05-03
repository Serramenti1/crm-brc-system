@include('partials.menu')

<h1>Nuovo Prodotto Fornitore</h1>

<form method="POST" action="/prodotti-fornitore">
@csrf

<p>
    Fornitore<br>
    <select name="fornitore_id">
        @foreach($fornitori as $f)
            <option value="{{ $f->id }}">{{ $f->ragione_sociale }}</option>
        @endforeach
    </select>
</p>

<p>
    Descrizione<br>
    <input type="text" name="descrizione" placeholder="Descrizione" required>
</p>

<p>
    Prezzo listino<br>
    <input type="number" name="prezzo_listino" step="0.01" placeholder="Listino">
</p>

<p>
    Sconto 1<br>
    <input type="number" name="sconto_1" step="0.01" placeholder="Sconto 1">
</p>

<p>
    Sconto 2<br>
    <input type="number" name="sconto_2" step="0.01" placeholder="Sconto 2">
</p>

<p>
    Sconto 3<br>
    <input type="number" name="sconto_3" step="0.01" placeholder="Sconto 3">
</p>

<p>
    <label>
        <input type="checkbox" name="bene_significativo" value="1">
        Bene significativo
    </label>
</p>

<button>Salva</button>

</form>