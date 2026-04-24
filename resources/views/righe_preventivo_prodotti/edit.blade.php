@include('partials.menu')

<h1>Modifica Riga Prodotto</h1>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/righe-preventivo-prodotti/{{ $riga->id }}">
    @csrf
    @method('PUT')

    <p>
        Modalità calcolo:<br>
        <select name="modalita_calcolo">
            <option value="da_listino" {{ $riga->modalita_calcolo == 'da_listino' ? 'selected' : '' }}>
                Da listino
            </option>
            <option value="da_costo_netto" {{ $riga->modalita_calcolo == 'da_costo_netto' ? 'selected' : '' }}>
                Da costo netto
            </option>
        </select>
    </p>

    <p>
        Fornitore:<br>
        <select name="fornitore_id">
            <option value="">-- Nessun fornitore --</option>
            @foreach($fornitori as $fornitore)
                <option value="{{ $fornitore->id }}" {{ $riga->fornitore_id == $fornitore->id ? 'selected' : '' }}>
                    {{ $fornitore->ragione_sociale }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        Descrizione:<br>
        <input type="text" name="descrizione" value="{{ $riga->descrizione }}">
    </p>

    <p>
        Quantità:<br>
        <input type="number" name="quantita" step="0.01" value="{{ $riga->quantita }}">
    </p>

    <p>
        Prezzo listino:<br>
        <input type="number" name="prezzo_listino" step="0.01" value="{{ $riga->prezzo_listino }}">
    </p>

    <p>
        Costo netto / prezzo scontato:<br>
        <input type="number" name="costo_netto" step="0.01" value="{{ $riga->costo_netto }}">
    </p>

    <p>
        Sconto fornitore 1:<br>
        <input type="number" name="sconto_fornitore_1" step="0.01" min="0" max="100" value="{{ $riga->sconto_fornitore_1 }}">
    </p>

    <p>
        Sconto fornitore 2:<br>
        <input type="number" name="sconto_fornitore_2" step="0.01" min="0" max="100" value="{{ $riga->sconto_fornitore_2 }}">
    </p>

    <p>
        Sconto fornitore 3:<br>
        <input type="number" name="sconto_fornitore_3" step="0.01" min="0" max="100" value="{{ $riga->sconto_fornitore_3 }}">
    </p>

    <p>
        Ricarico %:<br>
        <input type="number" name="ricarico_percentuale" step="0.01" value="{{ $riga->ricarico_percentuale }}">
    </p>

    <p>
        Ordine visualizzazione:<br>
        <input type="number" name="ordine_visualizzazione" value="{{ $riga->ordine_visualizzazione }}">
    </p>

    <p>
        Note:<br>
        <textarea name="note">{{ $riga->note }}</textarea>
    </p>

    <button type="submit">Aggiorna Riga Prodotto</button>
</form>

<br>

<a href="/preventivi/{{ $riga->preventivo_id }}">Torna al preventivo</a>