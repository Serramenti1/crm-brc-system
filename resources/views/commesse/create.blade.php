@include('partials.menu')

<h1>Nuova Commessa</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/commesse">
    @csrf

    <p>
        Cliente:<br>
        <select name="cliente_id">
            <option value="">-- Seleziona cliente --</option>
            @foreach($clienti as $cliente)
                <option value="{{ $cliente->id }}">
                    {{ $cliente->nome }} {{ $cliente->cognome }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        Titolo commessa:<br>
        <input type="text" name="titolo">
    </p>

    <p>
        Indirizzo lavoro:<br>
        <input type="text" name="indirizzo_lavoro">
    </p>

    <p>
        Città lavoro:<br>
        <input type="text" name="citta_lavoro">
    </p>

    <p>
        Provincia lavoro:<br>
        <input type="text" name="provincia_lavoro">
    </p>

    <p>
        CAP lavoro:<br>
        <input type="text" name="cap_lavoro">
    </p>

    <p>
        Tipologia abitazione:<br>
        <select name="tipologia_abitazione">
            <option value="">-- Seleziona --</option>
            <option value="principale">Prima casa</option>
            <option value="secondaria">Seconda casa</option>
        </select>
    </p>

    <p>
        Tipo lavoro:<br>
        <select name="tipo_lavoro">
            <option value="">-- Seleziona --</option>
            <option value="manutenzione">Manutenzione</option>
            <option value="ristrutturazione">Ristrutturazione</option>
            <option value="risparmio_energetico">Risparmio energetico</option>
        </select>
    </p>

    <p>
        Tipo detrazione:<br>
        <input type="text" name="tipo_detrazione">
    </p>

    <p>
        Percentuale detrazione:<br>
        <input type="number" name="percentuale_detrazione" min="0" max="100" step="0.01">
    </p>

    <p>
        Stato:<br>
        <select name="stato">
            <option value="aperta">Aperta</option>
            <option value="chiusa">Chiusa</option>
            <option value="preventivo">Preventivo</option>
            <option value="confermata">Confermata</option>
        </select>
    </p>

    <p>
        Note:<br>
        <textarea name="note"></textarea>
    </p>

    <button type="submit">Salva Commessa</button>
</form>

<br>

<a href="/commesse">Torna alla lista</a>