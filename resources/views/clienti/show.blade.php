@include('partials.menu')

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<h1>Dettaglio Cliente</h1>

<p><strong>Nome:</strong> {{ $cliente->nome }}</p>
<p><strong>Cognome:</strong> {{ $cliente->cognome }}</p>

<p><strong>Indirizzo:</strong><br>
{{ $cliente->indirizzo }}<br>
{{ $cliente->cap }} {{ $cliente->citta }} ({{ $cliente->provincia }})
</p>

<p><strong>Telefono:</strong> {{ $cliente->telefono }}</p>
<p><strong>Email:</strong> {{ $cliente->email }}</p>

<hr>

<p><strong>Codice fiscale:</strong> {{ $cliente->codice_fiscale }}</p>
<p><strong>Partita IVA:</strong> {{ $cliente->partita_iva }}</p>

@if($cliente->tipo_abitazione ?? false)
<p><strong>Tipo abitazione:</strong> {{ $cliente->tipo_abitazione }}</p>
@endif

@if($cliente->note ?? false)
<p><strong>Note:</strong><br>{{ $cliente->note }}</p>
@endif

<br>

<a href="/clienti">← Torna alla lista</a>