@include('partials.menu')

<h1>Modifica Cliente</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/clienti/{{ $cliente->id }}">
    @csrf
    @method('PUT')

    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">

        <p>
            Nome:<br>
            <input type="text" name="nome" value="{{ $cliente->nome }}" style="width:100%;">
        </p>

        <p>
            Cognome:<br>
            <input type="text" name="cognome" value="{{ $cliente->cognome }}" style="width:100%;">
        </p>

        <p>
            Telefono:<br>
            <input type="text" name="telefono" value="{{ $cliente->telefono }}" style="width:100%;">
        </p>

        <p>
            Email:<br>
            <input type="email" name="email" value="{{ $cliente->email }}" style="width:100%;">
        </p>

        <p>
            Codice fiscale:<br>
            <input type="text" name="codice_fiscale" value="{{ $cliente->codice_fiscale }}" style="width:100%;">
        </p>

        <p>
            Partita IVA:<br>
            <input type="text" name="partita_iva" value="{{ $cliente->partita_iva }}" style="width:100%;">
        </p>

        <p style="grid-column: span 2;">
            Indirizzo:<br>
            <input type="text" name="indirizzo" value="{{ $cliente->indirizzo }}" style="width:100%;">
        </p>

        <p>
            CAP:<br>
            <input type="text" name="cap" value="{{ $cliente->cap }}" style="width:100%;">
        </p>

        <p>
            Città:<br>
            <input type="text" name="citta" value="{{ $cliente->citta }}" style="width:100%;">
        </p>

        <p>
            Provincia:<br>
            <input type="text" name="provincia" value="{{ $cliente->provincia }}" style="width:100%;">
        </p>

        <p style="grid-column: span 3;">
            Note:<br>
            <textarea name="note" rows="4" style="width:100%;">{{ $cliente->note }}</textarea>
        </p>

    </div>

    <button type="submit">Aggiorna Cliente</button>
</form>

<br>

<a href="/clienti">← Torna ai clienti</a>