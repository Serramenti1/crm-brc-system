@include('partials.menu')

<style>
    .form-section {
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .form-field input,
    .form-field select,
    .form-field textarea {
        width: 100%;
        box-sizing: border-box;
    }

    .form-field-full {
        grid-column: span 3;
    }
    .form-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    height: 100%;
    padding-top: 22px;
}

    .form-checkbox input {
     width: auto;
}
</style>

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

<div class="form-section">
    <h3>Cliente e dati base</h3>

    <div class="form-grid">
        <div class="form-field">
            <label>Cliente</label><br>
            <select name="cliente_id" required>
                <option value="">-- Seleziona cliente --</option>
                @foreach($clienti as $cliente)
                    <option value="{{ $cliente->id }}">
                        {{ $cliente->nome }} {{ $cliente->cognome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-field">
            <label>Titolo commessa</label><br>
            <input type="text" name="titolo">
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Dati intervento</h3>

    <div class="form-grid">
        <div class="form-field">
            <label>Indirizzo intervento</label><br>
            <input type="text" name="indirizzo_lavoro">
        </div>

        <div class="form-field">
            <label>Città intervento</label><br>
            <input type="text" name="citta_lavoro">
        </div>

        <div class="form-field">
            <label>Provincia intervento</label><br>
            <input type="text" name="provincia_lavoro">
        </div>

        <div class="form-field">
            <label>CAP intervento</label><br>
            <input type="text" name="cap_lavoro">
        </div>

        <div class="form-field">
            <label>Piano di posa</label><br>
            <input type="number" name="piano_posa" min="0">
        </div>

       <div class="form-field form-checkbox">
    <input type="checkbox" name="autoscala" value="1">
    <label>Serve autoscala</label>
</div>
    </div>
</div>

<div class="form-section">
    <h3>Tipologia e detrazione</h3>

    <div class="form-grid">
        <div class="form-field">
            <label>Tipologia abitazione</label><br>
            <select name="tipologia_abitazione" id="tipologia_abitazione" onchange="aggiornaPercentualeDetrazione()">
                <option value="">-- Seleziona --</option>
                <option value="principale">Prima casa</option>
                <option value="secondaria">Seconda casa</option>
            </select>
        </div>

        <div class="form-field">
            <label>Tipo Intervento</label><br>
            <select name="tipo_lavoro">
                <option value="">-- Seleziona --</option>
                <option value="manutenzione">Manutenzione</option>
                <option value="ristrutturazione">Ristrutturazione</option>
                <option value="risparmio_energetico">Risparmio energetico</option>
            </select>
        </div>

        <div class="form-field">
            <label>Tipo detrazione</label><br>
            <select name="tipo_detrazione" id="tipo_detrazione" onchange="aggiornaPercentualeDetrazione()">
    <option value="">-- Nessuna detrazione --</option>
    @foreach($detrazioni as $detrazione)
        <option value="{{ $detrazione->nome }}">
            {{ $detrazione->nome }} - {{ number_format($detrazione->percentuale, 2, ',', '.') }}%
        </option>
    @endforeach
</select>
        </div>

        <div class="form-field">
            <label>Percentuale detrazione</label><br>
            <input type="number" id="percentuale_detrazione" name="percentuale_detrazione" min="0" max="100" step="0.01" readonly>
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Dati catastali</h3>

    <div class="form-grid">
        <div class="form-field-full">
            <label>Dati catastali</label><br>
            <textarea name="dati_catastali" rows="3"></textarea>
        </div>

        <div class="form-field">
            <label>Numero catastale</label><br>
            <input type="text" name="numero_catastale">
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Pratica edilizia</h3>

    <div class="form-grid">
        <div class="form-field">
            <label>Tipo pratica edilizia</label><br>
            <input type="text" name="pratica_edilizia_tipo" placeholder="Esempio: CILA, SCIA">
        </div>

        <div class="form-field">
            <label>Numero pratica edilizia</label><br>
            <input type="text" name="pratica_edilizia_numero">
        </div>

        <div class="form-field">
            <label>Protocollo pratica edilizia</label><br>
            <input type="text" name="pratica_edilizia_protocollo">
        </div>

        <div class="form-field form-checkbox">
    <input type="checkbox" name="pratica_enea" value="1">
    <label>Pratica ENEA</label>
</div>
    </div>
</div>

<div class="form-section">
    <h3>Note</h3>

    <textarea name="note" rows="4" style="width:100%; box-sizing:border-box;"></textarea>
</div>

<button type="submit">Salva Commessa</button>

</form>

<br>

<a href="/commesse">Torna alla lista</a>

<script>
let detrazioni = @json($detrazioni);

function aggiornaPercentualeDetrazione() {
    let tipoDetrazione = document.getElementById('tipo_detrazione').value;
    let inputPercentuale = document.getElementById('percentuale_detrazione');

    inputPercentuale.value = '';

    if (!tipoDetrazione) {
        return;
    }

    for (let i = 0; i < detrazioni.length; i++) {
        if (detrazioni[i].nome === tipoDetrazione) {
            inputPercentuale.value = detrazioni[i].percentuale;
            return;
        }
    }
}
</script>