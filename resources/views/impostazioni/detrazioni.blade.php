@include('partials.menu')

<h1>Gestione Detrazioni</h1>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<h3>Aggiungi detrazione</h3>

<form method="POST" action="/impostazioni/detrazioni">
@csrf

<p>
    Nome<br>
    <input type="text" name="nome" placeholder="Ecobonus prima casa" required>
</p>

<p>
    Percentuale %<br>
    <input type="number" name="percentuale" step="0.01" required>
</p>

<p>
    <label>
        <input type="checkbox" name="attiva" value="1" checked>
        Attiva
    </label>
</p>

<button>Salva</button>
</form>

<hr>

<h3>Detrazioni</h3>

<table border="1" cellpadding="5" width="100%">
<tr>
    <th>Nome</th>
    <th>%</th>
    <th>Attiva</th>
    <th>Azioni</th>
</tr>

@foreach($detrazioni as $d)
<tr>

<form method="POST" action="/impostazioni/detrazioni/{{ $d->id }}">
@csrf
@method('PUT')

<td>
    <input type="text" name="nome" value="{{ $d->nome }}">
</td>

<td>
    <input type="number" name="percentuale" step="0.01" value="{{ $d->percentuale }}">
</td>

<td>
    <input type="checkbox" name="attiva" value="1" {{ $d->attiva ? 'checked' : '' }}>
</td>

<td>
    <button>Salva</button>
</td>

</form>

</tr>
@endforeach

</table>

<br>
<a href="/impostazioni">← Torna alle impostazioni</a>