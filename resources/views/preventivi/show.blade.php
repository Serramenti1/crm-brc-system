@include('partials.menu')

<h1>Dettaglio Preventivo</h1>

<p><strong>ID Preventivo:</strong> {{ $preventivo->id }}</p>
<p><strong>Numero:</strong> {{ $preventivo->numero }}</p>
<p><strong>Versione:</strong> {{ $preventivo->versione }}</p>
<p><strong>Stato:</strong> {{ $preventivo->stato }}</p>

<p>
    <strong>Cliente:</strong>
    {{ $preventivo->commessa && $preventivo->commessa->cliente ? $preventivo->commessa->cliente->nome . ' ' . $preventivo->commessa->cliente->cognome : '' }}
</p>

<p>
    <strong>Commessa:</strong>
    {{ $preventivo->commessa ? $preventivo->commessa->titolo : '' }}
</p>

<hr>

<h2>Righe Prodotti</h2>

<a href="#form-aggiunta">+ Aggiungi riga prodotto</a>

<br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Modalità</th>
        <th>Fornitore</th>
        <th>Descrizione</th>
        <th>Qta</th>
        <th>Listino</th>
        <th>SC1</th>
        <th>SC2</th>
        <th>SC3</th>
        <th>Costo Netto</th>
        <th>Ricarico %</th>
        <th>Prezzo Cliente</th>
        <th>Sconto Cliente %</th>
        <th>Totale Cliente</th>
        <th>Azioni</th>
    </tr>

    @foreach($preventivo->righeProdotti as $riga)
    <tr>
        <td>{{ $riga->id }}</td>
        <td>{{ $riga->modalita_calcolo }}</td>
        <td>{{ $riga->fornitore ? $riga->fornitore->ragione_sociale : '' }}</td>
        <td>{{ $riga->descrizione }}</td>
        <td>{{ $riga->quantita }}</td>
        <td>{{ $riga->prezzo_listino }}</td>
        <td>{{ $riga->sconto_fornitore_1 }}%</td>
        <td>{{ $riga->sconto_fornitore_2 }}%</td>
        <td>{{ $riga->sconto_fornitore_3 }}%</td>
        <td>{{ $riga->costo_netto }}</td>
        <td>{{ $riga->ricarico_percentuale }}%</td>
        <td>{{ $riga->prezzo_cliente_unitario }}</td>
        <td>{{ $riga->sconto_cliente_percentuale }}%</td>
        <td>{{ $riga->totale_cliente }}</td>
        <td>
            <a href="/righe-preventivo-prodotti/{{ $riga->id }}/edit">Modifica</a>

            <form action="/righe-preventivo-prodotti/{{ $riga->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Sei sicuro di voler eliminare questa riga?')">
                    Elimina
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<hr>

<h2 id="form-aggiunta">Aggiungi Riga Prodotto</h2>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="/preventivi/{{ $preventivo->id }}/aggiungi-riga-prodotto">
    @csrf

    <p>
        Modalità calcolo:<br>
        <select name="modalita_calcolo">
            <option value="da_listino" {{ old('modalita_calcolo', 'da_listino') == 'da_listino' ? 'selected' : '' }}>
                Da listino
            </option>
            <option value="da_costo_netto" {{ old('modalita_calcolo') == 'da_costo_netto' ? 'selected' : '' }}>
                Da costo netto
            </option>
        </select>
    </p>

    <p>
        Fornitore:<br>
        <select name="fornitore_id">
            <option value="">-- Nessun fornitore --</option>
            @foreach($fornitori as $fornitore)
                <option value="{{ $fornitore->id }}" {{ old('fornitore_id') == $fornitore->id ? 'selected' : '' }}>
                    {{ $fornitore->ragione_sociale }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        Descrizione:<br>
        <input type="text" name="descrizione" value="{{ old('descrizione') }}">
    </p>

    <p>
        Quantità:<br>
        <input type="number" name="quantita" step="0.01" value="{{ old('quantita', 1) }}">
    </p>

    <p>
        Prezzo listino:<br>
        <input type="number" name="prezzo_listino" step="0.01" value="{{ old('prezzo_listino', 0) }}">
    </p>

    <p>
        Costo netto / prezzo scontato:<br>
        <input type="number" name="costo_netto" step="0.01" value="{{ old('costo_netto', 0) }}">
    </p>

    <p>
        Sconto fornitore 1:<br>
        <input type="number" name="sconto_fornitore_1" step="0.01" min="0" max="100" value="{{ old('sconto_fornitore_1', 0) }}">
    </p>

    <p>
        Sconto fornitore 2:<br>
        <input type="number" name="sconto_fornitore_2" step="0.01" min="0" max="100" value="{{ old('sconto_fornitore_2', 0) }}">
    </p>

    <p>
        Sconto fornitore 3:<br>
        <input type="number" name="sconto_fornitore_3" step="0.01" min="0" max="100" value="{{ old('sconto_fornitore_3', 0) }}">
    </p>

    <p>
        Ricarico %:<br>
        <input type="number" name="ricarico_percentuale" step="0.01" value="{{ old('ricarico_percentuale', 0) }}">
    </p>

    <p>
        Ordine visualizzazione:<br>
        <input type="number" name="ordine_visualizzazione" value="{{ old('ordine_visualizzazione', 0) }}">
    </p>

    <p>
        Note:<br>
        <textarea name="note">{{ old('note') }}</textarea>
    </p>

    <button type="submit">Salva Riga Prodotto</button>
</form>

<br>

<a href="/preventivi">Torna alla lista preventivi</a>