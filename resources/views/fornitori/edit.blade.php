@include('partials.menu')

<h1>Modifica Fornitore</h1>

<form method="POST" action="/fornitori/{{ $fornitore->id }}">
@csrf
@method('PUT')

<input type="text" name="ragione_sociale" value="{{ $fornitore->ragione_sociale }}" required>

<input type="text" name="referente" value="{{ $fornitore->referente }}">

<input type="text" name="telefono" value="{{ $fornitore->telefono }}">

<input type="email" name="email" value="{{ $fornitore->email }}">
@include('partials.menu')

<h1>Modifica Fornitore</h1>

<form method="POST" action="/fornitori/{{ $fornitore->id }}">
@csrf
@method('PUT')

<p>
    Ragione sociale:<br>
    <input type="text" name="ragione_sociale" value="{{ $fornitore->ragione_sociale }}" required>
</p>

<p>
    Referente:<br>
    <input type="text" name="referente" value="{{ $fornitore->referente }}">
</p>

<p>
    Telefono:<br>
    <input type="text" name="telefono" value="{{ $fornitore->telefono }}">
</p>

<p>
    Email:<br>
    <input type="email" name="email" value="{{ $fornitore->email }}">
</p>

<p>
    Note:<br>
    <textarea name="note" rows="4" cols="40">{{ $fornitore->note }}</textarea>
</p>

<button type="submit">Salva</button>

</form>

<br>

<a href="/fornitori">← Torna ai fornitori</a>
<textarea name="note">{{ $fornitore->note }}</textarea>

<button>Salva</button>

</form>