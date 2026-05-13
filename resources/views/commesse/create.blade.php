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
        padding: 10px 12px;
        min-height: 42px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
    }

    .form-field-full {
        grid-column: span 3;
    }

    .form-field textarea {
        min-height: 100px;
        resize: vertical;
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

    .cliente-selezionato-box {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        background: #f9fafb;
        min-height: 42px;
        box-sizing: border-box;
    }

    .modale-sfondo {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.45);
    }

    .modale-contenuto {
        background: white;
        width: 80%;
        max-width: 900px;
        margin: 60px auto;
        padding: 20px;
        border-radius: 8px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .riga-cliente-nascosta {
        display: none;
    }
</style>

<div class="container">

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

            <input type="hidden" id="cliente_id" name="cliente_id" required>

            <div id="cliente_selezionato" class="cliente-selezionato-box">
                Nessun cliente selezionato
            </div>

            <button type="button" class="btn btn-azione" onclick="apriModaleClienti()">
                Seleziona cliente
            </button>
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
            <input type="number" id="piano_posa" name="piano_posa" min="0">
        </div>

        <div class="form-field form-checkbox">
            <input type="checkbox" id="autoscala" name="autoscala" value="1">
            <label>Serve autoscala</label>
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Tipologia e detrazione</h3>

    <div class="form-grid">
        <div class="form-field">
            <label>Tipologia abitazione</label><br>
            <select name="tipologia_abitazione" id="tipologia_abitazione">
                <option value="">-- Seleziona --</option>
                <option value="prima_casa">Prima casa</option>
                <option value="seconda_casa">Seconda casa</option>
                <option value="condominio">Condominio</option>
                <option value="locale_commerciale">Locale commerciale</option>
                <option value="ufficio">Ufficio</option>
                <option value="capannone">Capannone</option>
            </select>
        </div>

        <div class="form-field">
            <label>Tipo intervento</label><br>
            <select name="tipo_intervento_id">
                <option value="">-- Seleziona --</option>

                @foreach($tipiIntervento as $tipo)
                    <option value="{{ $tipo->id }}">
                        {{ $tipo->nome }}
                    </option>
                @endforeach
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
            <input type="number"
                   id="percentuale_detrazione"
                   name="percentuale_detrazione"
                   min="0"
                   max="100"
                   step="0.01"
                   readonly>
        </div>

        <div class="form-field form-checkbox">
            <input type="checkbox" name="pratica_enea" value="1">
            <label>Pratica ENEA</label>
        </div>
    </div>
</div>

<div class="form-section">
    <h3>Dati catastali</h3>

    <div class="form-grid">
        <div class="form-field">
            <label>Foglio</label><br>
            <input type="text" name="foglio_catastale">
        </div>

        <div class="form-field">
            <label>Mappale</label><br>
            <input type="text" name="mappale_catastale">
        </div>

        <div class="form-field">
            <label>Particella</label><br>
            <input type="text" name="particella_catastale">
        </div>

        <div class="form-field">
            <label>Sub</label><br>
            <input type="text" name="sub_catastale">
        </div>

        <div class="form-field">
            <label>Numero catastale</label><br>
            <input type="text" name="numero_catastale">
        </div>

        <div class="form-field-full">
            <label>Note catastali</label><br>
            <textarea name="dati_catastali" rows="3"></textarea>
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
    </div>
</div>

<div class="form-section">
    <h3>Note</h3>
    <textarea name="note" rows="4" style="width:100%; box-sizing:border-box;"></textarea>
</div>

<div style="margin-top:20px; margin-bottom:30px; display:flex; gap:15px; align-items:center;">
    <button type="submit" class="btn btn-azione">
        Salva Commessa
    </button>

    <a href="/commesse" class="btn btn-azione">
        Torna alla lista
    </a>
</div>

</form>

</div>

<div id="modale_clienti" class="modale-sfondo">

    <div class="modale-contenuto">

        <h2>Seleziona cliente</h2>

        <input type="text"
               id="ricerca_cliente"
               placeholder="Cerca per nome, cognome, città, telefono..."
               onkeyup="filtraClienti()">

        <table class="tabella-lista">

            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Città</th>
                <th>Telefono</th>
                <th>Azioni</th>
            </tr>

            @foreach($clienti as $cliente)

                <tr class="riga-cliente"
                    data-ricerca="{{ strtolower($cliente->nome . ' ' . $cliente->cognome . ' ' . $cliente->citta . ' ' . $cliente->telefono) }}">

                    <td>{{ $cliente->nome }}</td>
                    <td>{{ $cliente->cognome }}</td>
                    <td>{{ $cliente->citta }}</td>
                    <td>{{ $cliente->telefono }}</td>

                    <td>
                        <button type="button"
                                class="btn btn-azione"
                                onclick="selezionaCliente(
                                    '{{ $cliente->id }}',
                                    '{{ addslashes($cliente->nome . ' ' . $cliente->cognome) }}'
                                )">
                            Seleziona
                        </button>
                    </td>

                </tr>

            @endforeach

        </table>

        <div style="margin-top:20px;">
            <button type="button" class="btn btn-azione" onclick="chiudiModaleClienti()">
                Chiudi
            </button>
        </div>

    </div>

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

function aggiornaAutoscalaAutomatica() {
    let piano = parseInt(document.getElementById('piano_posa').value || 0);
    let autoscala = document.getElementById('autoscala');

    autoscala.checked = piano >= 3;
}

document.addEventListener('DOMContentLoaded', function () {
    let piano = document.getElementById('piano_posa');

    if (piano) {
        piano.addEventListener('input', aggiornaAutoscalaAutomatica);
    }
});

function apriModaleClienti() {
    document.getElementById('modale_clienti').style.display = 'block';
    document.getElementById('ricerca_cliente').value = '';
    filtraClienti();
    document.getElementById('ricerca_cliente').focus();
}

function chiudiModaleClienti() {
    document.getElementById('modale_clienti').style.display = 'none';
}

function selezionaCliente(id, nome) {
    document.getElementById('cliente_id').value = id;
    document.getElementById('cliente_selezionato').innerHTML = nome;
    chiudiModaleClienti();
}

function filtraClienti() {
    let testo = document.getElementById('ricerca_cliente').value.toLowerCase();
    let righe = document.querySelectorAll('.riga-cliente');

    righe.forEach(function (riga) {
        let contenuto = riga.dataset.ricerca;

        if (contenuto.includes(testo)) {
            riga.classList.remove('riga-cliente-nascosta');
        } else {
            riga.classList.add('riga-cliente-nascosta');
        }
    });
}

window.onclick = function(event) {
    let modale = document.getElementById('modale_clienti');

    if (event.target === modale) {
        chiudiModaleClienti();
    }
}
</script>