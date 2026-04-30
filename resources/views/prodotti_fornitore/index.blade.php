@include('partials.menu')

<h1>Prodotti Fornitore</h1>

<form method="GET" action="/prodotti-fornitore" style="margin-bottom:15px;">
    <input type="text" name="q" placeholder="Cerca prodotto o fornitore..." value="{{ request('q') }}">
    <button type="submit">Cerca</button>
    <a href="/prodotti-fornitore">Reset</a>
</form>

<a href="/prodotti-fornitore/create">+ Nuovo</a>

<table border="1">
<tr>
<th>Fornitore</th>
<th>Descrizione</th>
<th>Listino</th>
<th>Sconti</th>
<th>Azioni</th>
</tr>

@foreach($prodotti as $p)
<tr>
<td>{{ $p->fornitore->ragione_sociale }}</td>
<td>{{ $p->descrizione }}</td>
<td>{{ $p->prezzo_listino }}</td>
<td>{{ $p->sconto_1 }} / {{ $p->sconto_2 }} / {{ $p->sconto_3 }}</td>
<td>
<a href="/prodotti-fornitore/{{ $p->id }}/edit">Modifica</a>

<form method="POST" action="/prodotti-fornitore/{{ $p->id }}">
@csrf
@method('DELETE')
<button>Elimina</button>
</form>

</td>
</tr>
@endforeach

</table>