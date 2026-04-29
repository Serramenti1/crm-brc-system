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

<input type="text" name="descrizione" value="{{ $prodotto->descrizione }}">

<input type="number" name="prezzo_listino" value="{{ $prodotto->prezzo_listino }}">

<input type="number" name="sconto_1" value="{{ $prodotto->sconto_1 }}">
<input type="number" name="sconto_2" value="{{ $prodotto->sconto_2 }}">
<input type="number" name="sconto_3" value="{{ $prodotto->sconto_3 }}">

<button>Salva</button>

</form>