@include('partials.menu')

<h1>Fornitori</h1>

<a href="/fornitori/create">+ Nuovo Fornitore</a>

<br><br>

<table border="1">
<tr>
<th>Nome</th>
<th>Telefono</th>
<th>Email</th>
<th>Azioni</th>
</tr>

@foreach($fornitori as $f)
<tr>
<td>{{ $f->ragione_sociale }}</td>
<td>{{ $f->telefono }}</td>
<td>{{ $f->email }}</td>
<td>

<a href="/fornitori/{{ $f->id }}/edit">Modifica</a>

<form method="POST" action="/fornitori/{{ $f->id }}" style="display:inline;">
@csrf
@method('DELETE')
<button>Elimina</button>
</form>

</td>
</tr>
@endforeach

</table>