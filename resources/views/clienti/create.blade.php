@include('partials.menu')

<h1>Nuovo Cliente</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/clienti">
    @csrf

    <p>
        Nome:<br>
        <input type="text" name="nome">
    </p>

    <p>
        Cognome:<br>
        <input type="text" name="cognome">
    </p>

    <p>
        Telefono:<br>
        <input type="text" name="telefono">
    </p>

    <p>
        Email:<br>
        <input type="text" name="email">
    </p>

    <p>
        Indirizzo:<br>
        <input type="text" name="indirizzo">
    </p>

    <p>
        Città:<br>
        <input type="text" name="citta">
    </p>

    <p>
        CAP:<br>
        <input type="text" name="cap">
    </p>

    <p>
        Provincia:<br>
        <input type="text" name="provincia">
    </p>

    <p>
        Codice Fiscale:<br>
        <input type="text" name="codice_fiscale">
    </p>

    <p>
        Partita IVA:<br>
        <input type="text" name="partita_iva">
    </p>

    <button type="submit">Salva Cliente</button>
</form>

<br>

<a href="/clienti">Torna alla lista</a>