@include('partials.menu')

<h1>Modifica Fornitore</h1>

<form method="POST" action="/fornitori/{{ $fornitore->id }}">
@csrf
@method('PUT')

<input type="text" name="ragione_sociale" value="{{ $fornitore->ragione_sociale }}" required>

<input type="text" name="referente" value="{{ $fornitore->referente }}">

<input type="text" name="telefono" value="{{ $fornitore->telefono }}">

<input type="email" name="email" value="{{ $fornitore->email }}">

<textarea name="note">{{ $fornitore->note }}</textarea>

<button>Salva</button>

</form>