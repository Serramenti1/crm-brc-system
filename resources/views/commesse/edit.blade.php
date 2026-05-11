@include('partials.menu')

<div class="container">

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

        <div class="form-section">
            <h3>Cliente e dati base</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Cliente</label>
                    <select name="cliente_id" required>
                        <option value="">-- Seleziona cliente --</option>
                        @foreach($clienti as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $commessa->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }} {{ $cliente->cognome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Titolo commessa</label>
                    <input type="text" name="titolo" value="{{ old('titolo', $commessa->titolo) }}">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Dati intervento</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Indirizzo intervento</label>
                    <input type="text" name="indirizzo_lavoro" value="{{ old('indirizzo_lavoro', $commessa->indirizzo_lavoro) }}">
                </div>

                <div class="form-field">
                    <label>Città intervento</label>
                    <input type="text" name="citta_lavoro" value="{{ old('citta_lavoro', $commessa->citta_lavoro) }}">
                </div>

                <div class="form-field">
                    <label>Provincia intervento</label>
                    <input type="text" name="provincia_lavoro" value="{{ old('provincia_lavoro', $commessa->provincia_lavoro) }}">
                </div>

                <div class="form-field">
                    <label>CAP intervento</label>
                    <input type="text" name="cap_lavoro" value="{{ old('cap_lavoro', $commessa->cap_lavoro) }}">
                </div>

                <div class="form-field">
                    <label>Piano di posa</label>
                    <input type="number" name="piano_posa" min="0" value="{{ old('piano_posa', $commessa->piano_posa) }}">
                </div>

                <div class="form-field form-checkbox">
                    <input type="checkbox" name="autoscala" value="1" {{ old('autoscala', $commessa->autoscala) ? 'checked' : '' }}>
                    <label>Serve autoscala</label>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Tipologia e detrazione</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Tipologia abitazione</label>
                    <select name="tipologia_abitazione" id="tipologia_abitazione">
                        <option value="">-- Seleziona --</option>
                        <option value="prima_casa" {{ old('tipologia_abitazione', $commessa->tipologia_abitazione) == 'prima_casa' ? 'selected' : '' }}>Prima casa</option>
                        <option value="seconda_casa" {{ old('tipologia_abitazione', $commessa->tipologia_abitazione) == 'seconda_casa' ? 'selected' : '' }}>Seconda casa</option>
                        <option value="condominio" {{ old('tipologia_abitazione', $commessa->tipologia_abitazione) == 'condominio' ? 'selected' : '' }}>Condominio</option>
                        <option value="locale_commerciale" {{ old('tipologia_abitazione', $commessa->tipologia_abitazione) == 'locale_commerciale' ? 'selected' : '' }}>Locale commerciale</option>
                        <option value="ufficio" {{ old('tipologia_abitazione', $commessa->tipologia_abitazione) == 'ufficio' ? 'selected' : '' }}>Ufficio</option>
                        <option value="capannone" {{ old('tipologia_abitazione', $commessa->tipologia_abitazione) == 'capannone' ? 'selected' : '' }}>Capannone</option>
                    </select>
                </div>

                <div class="form-field">

    <label>Tipo intervento</label>

    <select name="tipo_intervento_id">

        <option value="">
            -- Seleziona --
        </option>

        @foreach($tipiIntervento as $tipo)

            <option value="{{ $tipo->id }}"
                {{ old('tipo_intervento_id', $commessa->tipo_intervento_id) == $tipo->id ? 'selected' : '' }}>

                {{ $tipo->nome }}

            </option>

        @endforeach

    </select>

</div>

                <div class="form-field">
                    <label>Tipo detrazione</label>
                    <select name="tipo_detrazione" id="tipo_detrazione" onchange="aggiornaPercentualeDetrazione()">
                        <option value="">-- Nessuna detrazione --</option>
                        @foreach($detrazioni as $detrazione)
                            <option value="{{ $detrazione->nome }}" {{ old('tipo_detrazione', $commessa->tipo_detrazione) == $detrazione->nome ? 'selected' : '' }}>
                                {{ $detrazione->nome }} - {{ number_format($detrazione->percentuale, 2, ',', '.') }}%
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label>Percentuale detrazione</label>
                    <input type="number" id="percentuale_detrazione" name="percentuale_detrazione" min="0" max="100" step="0.01" value="{{ old('percentuale_detrazione', $commessa->percentuale_detrazione) }}" readonly>
                </div>
                <div class="form-field form-checkbox">
                    <input type="checkbox" name="pratica_enea" value="1" {{ old('pratica_enea', $commessa->pratica_enea) ? 'checked' : '' }}>
                    <label>Pratica ENEA</label>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Dati catastali</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Foglio</label>
                    <input type="text" name="foglio_catastale" value="{{ old('foglio_catastale', $commessa->foglio_catastale) }}">
                </div>

                <div class="form-field">
                    <label>Mappale</label>
                    <input type="text" name="mappale_catastale" value="{{ old('mappale_catastale', $commessa->mappale_catastale) }}">
                </div>

                <div class="form-field">
                    <label>Particella</label>
                    <input type="text" name="particella_catastale" value="{{ old('particella_catastale', $commessa->particella_catastale) }}">
                </div>

                <div class="form-field">
                    <label>Sub</label>
                    <input type="text" name="sub_catastale" value="{{ old('sub_catastale', $commessa->sub_catastale) }}">
                </div>

                <div class="form-field">
                    <label>Numero catastale</label>
                    <input type="text" name="numero_catastale" value="{{ old('numero_catastale', $commessa->numero_catastale) }}">
                </div>

                <div class="form-field-full">
                    <label>Note catastali</label>
                    <textarea name="dati_catastali" rows="3">{{ old('dati_catastali', $commessa->dati_catastali) }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Pratica edilizia</h3>

            <div class="form-grid">
                <div class="form-field">
                    <label>Tipo pratica edilizia</label>
                    <input type="text" name="pratica_edilizia_tipo" value="{{ old('pratica_edilizia_tipo', $commessa->pratica_edilizia_tipo) }}" placeholder="Esempio: CILA, SCIA">
                </div>

                <div class="form-field">
                    <label>Numero pratica edilizia</label>
                    <input type="text" name="pratica_edilizia_numero" value="{{ old('pratica_edilizia_numero', $commessa->pratica_edilizia_numero) }}">
                </div>

                <div class="form-field">
                    <label>Protocollo pratica edilizia</label>
                    <input type="text" name="pratica_edilizia_protocollo" value="{{ old('pratica_edilizia_protocollo', $commessa->pratica_edilizia_protocollo) }}">
                </div>

                
            </div>
        </div>

        <div class="form-section">
            <h3>Note</h3>
            <textarea name="note" rows="4">{{ old('note', $commessa->note) }}</textarea>
        </div>

        <button type="submit" class="btn btn-azione">
            Aggiorna Commessa
        </button>

        <a href="/commesse" class="btn btn-azione">
            Torna alla lista
        </a>

    </form>

</div>

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