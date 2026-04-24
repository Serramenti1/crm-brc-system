<h1>Nuova Riga Prodotto Preventivo</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/righe-preventivo-prodotti">
    @csrf

    <p>
        Preventivo:<br>
        <select name="preventivo_id">
            <option value="">-- Seleziona preventivo --</option>
            @foreach($preventivi as $preventivo)
                <option value="{{ $preventivo->id }}">
                    Preventivo {{ $preventivo->numero }} - 
                    {{ $preventivo->commessa ? $preventivo->commessa->titolo : '' }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        Fornitore:<br>
        <select name="fornitore_id">
            <option value="">-- Nessun fornitore --</option>
            @foreach($fornitori as $fornitore)
                <option value="{{ $fornitore->id }}">
                    {{ $fornitore->ragione_sociale }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        Descrizione:<br>
        <input type="text" name="descrizione">
    </p>

    <p>
        Quantità:<br>
        <input type="number" name="quantita" step="0.01" value="1">
    </p>

    <p>
        Prezzo listino:<br>
        <input type="number" name="prezzo_listino" step="0.01" value="0">
    </p>

    <p>
        Sconto fornitore 1:<br>
        <input type="number" name="sconto_fornitore_1" step="0.01" min="0" max="100" value="0">
    </p>

    <p>
        Sconto fornitore 2:<br>
        <input type="number" name="sconto_fornitore_2" step="0.01" min="0" max="100" value="0">
    </p>

    <p>
        Sconto fornitore 3:<br>
        <input type="number" name="sconto_fornitore_3" step="0.01" min="0" max="100" value="0">
    </p>

    <p>
        Ricarico %:<br>
        <input type="number" name="ricarico_percentuale" step="0.01" value="0">
    </p>

    <p>
        Ordine visualizzazione:<br>
        <input type="number" name="ordine_visualizzazione" value="0">
    </p>

    <p>
        Note:<br>
        <textarea name="note"></textarea>
    </p>

    <button type="submit">Salva Riga Prodotto</button>
</form>

<br>

<a href="/righe-preventivo-prodotti">Torna alla lista</a>