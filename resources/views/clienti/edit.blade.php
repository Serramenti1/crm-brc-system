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

    <p>
        Nome:<br>
        <input type="text" name="nome" value="{{ $cliente->nome }}">
    </p>

    <p>
        Cognome:<br>
        <input type="text" name="cognome" value="{{ $cliente->cognome }}">
    </p>

    <p>
        Telefono:<br>
        <input type="text" name="telefono" value="{{ $cliente->telefono }}">
    </p>

    <p>
        Email:<br>
        <input type="text" name="email" value="{{ $cliente->email }}">
    </p>

    <p>
        Indirizzo:<br>
        <input type="text" name="indirizzo" value="{{ $cliente->indirizzo }}">
    </p>

    <p>
        Città:<br>
        <input type="text" name="citta" value="{{ $cliente->citta }}">
    </p>

    <p>
        CAP:<br>
        <input type="text" name="cap" value="{{ $cliente->cap }}">
    </p>

    <p>
        Provincia:<br>
        <input type="text" name="provincia" value="{{ $cliente->provincia }}">
    </p>

    <p>
        Codice Fiscale:<br>
        <input type="text" name="codice_fiscale" value="{{ $cliente->codice_fiscale }}">
    </p>

    <p>
        Partita IVA:<br>
        <input type="text" name="partita_iva" value="{{ $cliente->partita_iva }}">
    </p>

    <button type="submit">Aggiorna Cliente</button>
</form>

<br>

<a href="/clienti">Torna alla lista</a>