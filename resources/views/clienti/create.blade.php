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

    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">

        <p>
            Nome:<br>
            <input type="text" name="nome" style="width:100%;">
        </p>

        <p>
            Cognome:<br>
            <input type="text" name="cognome" style="width:100%;">
        </p>

        <p>
            Telefono:<br>
            <input type="text" name="telefono" style="width:100%;">
        </p>

        <p>
            Email:<br>
            <input type="email" name="email" style="width:100%;">
        </p>

        <p>
            Codice fiscale:<br>
            <input type="text" name="codice_fiscale" style="width:100%;">
        </p>

        <p>
            Partita IVA:<br>
            <input type="text" name="partita_iva" style="width:100%;">
        </p>

        <p style="grid-column: span 2;">
            Indirizzo:<br>
            <input type="text" name="indirizzo" style="width:100%;">
        </p>

        <p>
            CAP:<br>
            <input type="text" name="cap" style="width:100%;">
        </p>

        <p>
            Città:<br>
            <input type="text" name="citta" style="width:100%;">
        </p>

        <p>
            Provincia:<br>
            <input type="text" name="provincia" style="width:100%;">
        </p>

        <p style="grid-column: span 3;">
            Note:<br>
            <textarea name="note" rows="4" style="width:100%;"></textarea>
        </p>

    </div>

    <button type="submit">Salva Cliente</button>
</form>

<br>

<a href="/clienti">← Torna ai clienti</a>