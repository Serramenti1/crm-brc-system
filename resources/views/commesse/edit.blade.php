@include('partials.menu')

<h1>Modifica Commessa</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/commesse/{{ $commessa->id }}">
    @csrf
    @method('PUT')

    <p>
        Cliente:<br>
        <select name="cliente_id">
            <option value="">-- Seleziona cliente --</option>
            @foreach($clienti as $cliente)
                <option value="{{ $cliente->id }}" {{ $commessa->cliente_id == $cliente->id ? 'selected' : '' }}>
                    {{ $cliente->nome }} {{ $cliente->cognome }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        Titolo commessa:<br>
        <input type="text" name="titolo" value="{{ $commessa->titolo }}">
    </p>

    <p>
        Indirizzo lavoro:<br>
        <input type="text" name="indirizzo_lavoro" value="{{ $commessa->indirizzo_lavoro }}">
    </p>

    <p>
        Città lavoro:<br>
        <input type="text" name="citta_lavoro" value="{{ $commessa->citta_lavoro }}">
    </p>

    <p>
        Provincia lavoro:<br>
        <input type="text" name="provincia_lavoro" value="{{ $commessa->provincia_lavoro }}">
    </p>

    <p>
        CAP lavoro:<br>
        <input type="text" name="cap_lavoro" value="{{ $commessa->cap_lavoro }}">
    </p>

    <p>
        Tipologia abitazione:<br>
        <select name="tipologia_abitazione">
            <option value="">-- Seleziona --</option>
            <option value="principale" {{ $commessa->tipologia_abitazione == 'principale' ? 'selected' : '' }}>Prima casa</option>
            <option value="secondaria" {{ $commessa->tipologia_abitazione == 'secondaria' ? 'selected' : '' }}>Seconda casa</option>
        </select>
    </p>

    <p>
        Tipo lavoro:<br>
        <select name="tipo_lavoro">
            <option value="">-- Seleziona --</option>
            <option value="manutenzione" {{ $commessa->tipo_lavoro == 'manutenzione' ? 'selected' : '' }}>Manutenzione</option>
            <option value="ristrutturazione" {{ $commessa->tipo_lavoro == 'ristrutturazione' ? 'selected' : '' }}>Ristrutturazione</option>
            <option value="risparmio_energetico" {{ $commessa->tipo_lavoro == 'risparmio_energetico' ? 'selected' : '' }}>Risparmio energetico</option>
        </select>
    </p>

    <p>
        Tipo detrazione:<br>
        <input type="text" name="tipo_detrazione" value="{{ $commessa->tipo_detrazione }}">
    </p>

    <p>
        Percentuale detrazione:<br>
        <input type="number" name="percentuale_detrazione" min="0" max="100" step="0.01" value="{{ $commessa->percentuale_detrazione }}">
    </p>

    <p>
        Stato:<br>
        <select name="stato">
            <option value="aperta" {{ $commessa->stato == 'aperta' ? 'selected' : '' }}>Aperta</option>
            <option value="chiusa" {{ $commessa->stato == 'chiusa' ? 'selected' : '' }}>Chiusa</option>
            <option value="preventivo" {{ $commessa->stato == 'preventivo' ? 'selected' : '' }}>Preventivo</option>
            <option value="confermata" {{ $commessa->stato == 'confermata' ? 'selected' : '' }}>Confermata</option>
        </select>
    </p>

    <p>
        Note:<br>
        <textarea name="note">{{ $commessa->note }}</textarea>
    </p>

    <button type="submit">Aggiorna Commessa</button>
</form>

<br>

<a href="/commesse">Torna alla lista</a>