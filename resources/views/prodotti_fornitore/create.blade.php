@include('partials.menu')

<h1>Nuovo Prodotto Fornitore</h1>

<form method="POST" action="/prodotti-fornitore">
@csrf

<select name="fornitore_id">
@foreach($fornitori as $f)
<option value="{{ $f->id }}">{{ $f->ragione_sociale }}</option>
@endforeach
</select>

<input type="text" name="descrizione" placeholder="Descrizione">

<input type="number" name="prezzo_listino" placeholder="Listino">

<input type="number" name="sconto_1" placeholder="Sconto 1">
<input type="number" name="sconto_2" placeholder="Sconto 2">
<input type="number" name="sconto_3" placeholder="Sconto 3">

<button>Salva</button>

</form>