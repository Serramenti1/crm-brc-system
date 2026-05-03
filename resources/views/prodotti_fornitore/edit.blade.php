@include('partials.menu')

<h1>Modifica</h1>

<form method="POST" action="/prodotti-fornitore/{{ $prodotto->id }}">
@csrf
@method('PUT')

<select name="fornitore_id">
@foreach($fornitori as $f)
<option value="{{ $f->id }}" {{ $prodotto->fornitore_id == $f->id ? 'selected' : '' }}>
{{ $f->ragione_sociale }}
</option>
@endforeach
</select>
@include('partials.menu')

<h1>Modifica Prodotto Fornitore</h1>

<form method="POST" action="/prodotti-fornitore/{{ $prodotto->id }}">
@csrf
@method('PUT')

<p>
    Fornitore<br>
    <select name="fornitore_id">
        @foreach($fornitori as $f)
            <option value="{{ $f->id }}" {{ $prodotto->fornitore_id == $f->id ? 'selected' : '' }}>
                {{ $f->ragione_sociale }}
            </option>
        @endforeach
    </select>
</p>

<p>
    Descrizione<br>
    <input type="text" name="descrizione" value="{{ $prodotto->descrizione }}" required>
</p>

<p>
    Prezzo listino<br>
    <input type="number" name="prezzo_listino" value="{{ $prodotto->prezzo_listino }}" step="0.01">
</p>

<p>
    Sconto 1<br>
    <input type="number" name="sconto_1" value="{{ $prodotto->sconto_1 }}" step="0.01">
</p>

<p>
    Sconto 2<br>
    <input type="number" name="sconto_2" value="{{ $prodotto->sconto_2 }}" step="0.01">
</p>

<p>
    Sconto 3<br>
    <input type="number" name="sconto_3" value="{{ $prodotto->sconto_3 }}" step="0.01">
</p>

<p>
    <label>
        <input type="checkbox" name="bene_significativo" value="1" {{ $prodotto->bene_significativo ? 'checked' : '' }}>
        Bene significativo
    </label>
</p>

<button>Salva</button>

</form>
<input type="text" name="descrizione" value="{{ $prodotto->descrizione }}">

<input type="number" name="prezzo_listino" value="{{ $prodotto->prezzo_listino }}">

<input type="number" name="sconto_1" value="{{ $prodotto->sconto_1 }}">
<input type="number" name="sconto_2" value="{{ $prodotto->sconto_2 }}">
<input type="number" name="sconto_3" value="{{ $prodotto->sconto_3 }}">

<button>Salva</button>

</form>