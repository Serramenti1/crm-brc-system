@include('partials.menu')

@if(session('success'))
    <div style="color:green; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="color:red; margin-bottom:15px;">
        {{ session('error') }}
    </div>
@endif

<div class="container">

    <div style="margin-bottom:20px;">
        <a href="/ordini/stato/{{ $ordine->stato }}" class="btn btn-azione">
            ← Torna alla lista ordini
        </a>
    </div>

    <h1>Ordine {{ $ordine->numero }}</h1>

    <table class="tabella-dettaglio">

        <tr>
            <th colspan="2">Dettagli ordine</th>
        </tr>

        <tr>
            <td><strong>Cliente</strong></td>
            <td>
                {{ $ordine->commessa && $ordine->commessa->cliente
                    ? $ordine->commessa->cliente->nome . ' ' . $ordine->commessa->cliente->cognome
                    : '' }}
            </td>
        </tr>

        <tr>
            <td><strong>Commessa</strong></td>
            <td>{{ $ordine->commessa ? $ordine->commessa->titolo : '' }}</td>
        </tr>

        <tr>
            <td><strong>Indirizzo lavoro</strong></td>
            <td>{{ $ordine->commessa ? $ordine->commessa->indirizzo_lavoro : '' }}</td>
        </tr>

        <tr>
            <td><strong>Stato</strong></td>
            <td>

                @if($ordine->stato == 'preparazione_contratto')
                    Preparazione contratto
                @elseif($ordine->stato == 'in_lavorazione')
                    In lavorazione
                @elseif($ordine->stato == 'completo_attesa_merce')
                    Completo - attesa merce
                @elseif($ordine->stato == 'attesa_saldo_merce')
                    Attesa saldo merce
                @elseif($ordine->stato == 'programmare_posa')
                    Programmare posa
                @elseif($ordine->stato == 'concluso')
                    Concluso
                @elseif($ordine->stato == 'archiviato')
                    Archiviato
                @endif

            </td>
        </tr>

    </table>

    @if($ordine->stato != 'preparazione_contratto')

        <form method="POST"
              action="/ordini/{{ $ordine->id }}/stato-precedente"
              style="margin-top:15px;"
              onsubmit="return confermaRitornoStato()">

            @csrf

            <button type="submit" class="btn btn-azione">
                ← Torna allo stato precedente
            </button>

        </form>

    @endif

    <br>

    <h2>Prodotti e servizi</h2>

    @if($ordine->stato == 'preparazione_contratto')

        <details style="margin-bottom:20px;">

            <summary>
                <strong>+ Aggiungi prodotto</strong>
            </summary>

            <div style="margin-top:15px; border:1px solid #ccc; padding:15px; background:#fff;">

                <form method="POST"
                      action="/ordini/{{ $ordine->id }}/righe-prodotti">

                    @csrf

                    <p>
                        Fornitore<br>

                        <select name="fornitore_id"
                                id="fornitore_select_nuovo"
                                onchange="caricaProdottiNuovo()">

                            <option value="">
                                -- Seleziona --
                            </option>

                            @foreach($fornitori as $fornitore)

                                <option value="{{ $fornitore->id }}">
                                    {{ $fornitore->ragione_sociale }}
                                </option>

                            @endforeach

                        </select>
                    </p>

                    <p>
                        Prodotto fornitore<br>

                        <select id="prodotto_select_nuovo"
                                onchange="compilaProdottoNuovo()">

                            <option value="">
                                -- Seleziona --
                            </option>

                            @foreach($prodottiFornitore as $prodotto)

                                <option
                                    value="{{ $prodotto->id }}"
                                    data-fornitore="{{ $prodotto->fornitore_id }}"
                                    data-descrizione="{{ $prodotto->descrizione }}"
                                    data-listino="{{ $prodotto->prezzo_listino }}"
                                    data-s1="{{ $prodotto->sconto_1 }}"
                                    data-s2="{{ $prodotto->sconto_2 }}"
                                    data-s3="{{ $prodotto->sconto_3 }}"
                                    data-bene="{{ $prodotto->bene_significativo }}">
                                    {{ $prodotto->descrizione }}
                                </option>

                            @endforeach

                        </select>
                    </p>

                    <p>
                        Descrizione<br>

                        <input type="text"
                               name="descrizione"
                               id="descrizione_nuovo"
                               required>
                    </p>

                    <p>
                        Quantità<br>

                        <input type="number"
                               name="quantita"
                               value="1"
                               step="0.01">
                    </p>

                    <p>

                        <strong>Tipo prezzo</strong>

                        <div style="display:flex; gap:25px; margin-top:10px;">

                            <label>
                                <input type="radio"
                                       name="modalita_calcolo"
                                       value="da_listino"
                                       checked
                                       onclick="cambiaPrezzoNuovo()">

                                Listino
                            </label>

                            <label>
                                <input type="radio"
                                       name="modalita_calcolo"
                                       value="da_costo_netto"
                                       onclick="cambiaPrezzoNuovo()">

                                Scontato
                            </label>

                        </div>

                    </p>

                    <p id="label_prezzo_nuovo">
                        Prezzo listino
                    </p>

                    <input type="number"
                           id="input_prezzo_nuovo"
                           name="prezzo_listino"
                           step="0.01">

                    <p>
                        Sconto 1 %<br>

                        <input type="number"
                               name="sconto_fornitore_1"
                               id="s1_nuovo"
                               step="0.01">
                    </p>

                    <p>
                        Sconto 2 %<br>

                        <input type="number"
                               name="sconto_fornitore_2"
                               id="s2_nuovo"
                               step="0.01">
                    </p>

                    <p>
                        Sconto 3 %<br>

                        <input type="number"
                               name="sconto_fornitore_3"
                               id="s3_nuovo"
                               step="0.01">
                    </p>

                    <p>
                        Ricarico %<br>

                        <input type="number"
                               name="ricarico_percentuale"
                               value="{{ $impostazioni->ricarico_prodotti ?? 0 }}"
                               step="0.01">
                    </p>

                    <p>

                        <label>

                            <input type="checkbox"
                                   name="bene_significativo"
                                   id="bene_nuovo"
                                   value="1">

                            Bene significativo

                        </label>

                    </p>

                    <p>
                        Note<br>

                        <textarea name="note"></textarea>
                    </p>

                    <button type="submit" class="btn btn-azione">
                        Salva prodotto
                    </button>

                </form>

            </div>

        </details>

    @endif

    <table class="tabella-lista">

        <tr>
            <th>Prodotto</th>
            <th>Prezzi</th>
            <th>Azioni</th>
            <th>Servizi</th>
            <th>Stati / PDF</th>
        </tr>

        @foreach($ordine->righe as $riga)

            <tr>

                <td>

                    <strong>{{ $riga->descrizione }}</strong><br>

                    Fornitore:
                    {{ $riga->fornitore ? $riga->fornitore->ragione_sociale : '-' }}
                    <br>

                    Quantità:
                    {{ $riga->quantita }}
                    <br>

                    Bene significativo:
                    {{ $riga->bene_significativo ? 'Sì' : 'No' }}

                </td>

                <td>

                    Listino:
                    {{ number_format($riga->prezzo_listino ?? 0, 2, ',', '.') }} €
                    <br>

                    Scontato:
                    {{ number_format($riga->costo_netto ?? 0, 2, ',', '.') }} €
                    <br>

                    Cliente unitario:
                    {{ number_format($riga->prezzo_cliente_unitario ?? 0, 2, ',', '.') }} €
                    <br>

                    Totale:
                    {{ number_format($riga->totale_cliente ?? 0, 2, ',', '.') }} €

                </td>

                <td>

                    @if($ordine->stato == 'preparazione_contratto')

                        <button type="button"
                                class="btn btn-azione"
                                onclick="apriModificaRigaOrdine({{ $riga->id }})">
                            Modifica
                        </button>

                        <form method="POST"
                              action="/righe-ordine-prodotto/{{ $riga->id }}"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-elimina"
                                    onclick="return confirm('Eliminare questo prodotto?')">
                                🗑️
                            </button>

                        </form>

                    @else
                        -
                    @endif

                </td>

                <td>

                    @foreach($riga->servizi as $servizio)

                        <div style="border-bottom:1px solid #ddd; padding-bottom:10px; margin-bottom:10px;">

                            <strong>{{ $servizio->tipo_servizio }}</strong><br>

                            {{ $servizio->descrizione }}<br>

                            Prezzo:
                            {{ number_format($servizio->prezzo_cliente * $riga->quantita, 2, ',', '.') }} €

                            @if($ordine->stato == 'preparazione_contratto')

                                <div style="margin-top:10px;">

                                    <button type="button"
                                            class="btn btn-azione"
                                            onclick="apriModificaServizioOrdine({{ $servizio->id }})">
                                        Modifica
                                    </button>

                                    <form method="POST"
                                          action="/servizi-riga-ordine/{{ $servizio->id }}"
                                          style="display:inline;">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-elimina"
                                                onclick="return confirm('Eliminare servizio?')">
                                            🗑️
                                        </button>

                                    </form>

                                </div>

                                <div id="edit_servizio_ordine_{{ $servizio->id }}"
                                     style="display:none; margin-top:15px; border:1px solid #ccc; padding:15px;">

                                    <form method="POST"
                                          action="/servizi-riga-ordine/{{ $servizio->id }}">

                                        @csrf
                                        @method('PUT')

                                        <p>
                                            Tipo servizio<br>

                                            <input type="text"
                                                   name="tipo_servizio"
                                                   value="{{ $servizio->tipo_servizio }}">
                                        </p>

                                        <p>
                                            Descrizione<br>

                                            <input type="text"
                                                   name="descrizione"
                                                   value="{{ $servizio->descrizione }}">
                                        </p>

                                        <p>
                                            Costo BRC<br>

                                            <input type="number"
                                                   name="costo_brc"
                                                   value="{{ $servizio->costo_brc }}"
                                                   step="0.01">
                                        </p>

                                        <p>
                                            Ricarico %<br>

                                            <input type="number"
                                                   name="ricarico_percentuale"
                                                   value="{{ $servizio->ricarico_percentuale }}"
                                                   step="0.01">
                                        </p>

                                        <button type="submit" class="btn btn-azione">
                                            Salva servizio
                                        </button>

                                    </form>

                                </div>

                            @endif

                        </div>

                    @endforeach

                    @if($ordine->stato == 'preparazione_contratto')

                        <details>

                            <summary>
                                <strong>+ Servizio</strong>
                            </summary>

                            <form method="POST"
                                  action="/righe-ordine/{{ $riga->id }}/servizi"
                                  style="margin-top:15px;">

                                @csrf

                                <p>
                                    Tipo servizio<br>

                                    <input type="text"
                                           name="tipo_servizio"
                                           required>
                                </p>

                                <p>
                                    Descrizione<br>

                                    <input type="text"
                                           name="descrizione">
                                </p>

                                <p>
                                    Costo BRC<br>

                                    <input type="number"
                                           name="costo_brc"
                                           step="0.01">
                                </p>

                                <p>
                                    Ricarico %<br>

                                    <input type="number"
                                           name="ricarico_percentuale"
                                           step="0.01">
                                </p>

                                <button type="submit" class="btn btn-azione">
                                    Salva servizio
                                </button>

                            </form>

                        </details>

                    @endif

                </td>

                <td>

    @if($ordine->stato == 'preparazione_contratto')

        In attesa contratto

    @elseif($ordine->stato == 'in_lavorazione')

        <form id="form_riga_{{ $riga->id }}"
              method="POST"
              action="/righe-ordine/{{ $riga->id }}/aggiorna"
              enctype="multipart/form-data"
              onsubmit="return confermaAvanzamentoRiga(this)">

            @csrf

            <label>
                <input type="checkbox"
                       class="chk-inviato"
                       name="inviato"
                       value="1"
                       {{ $riga->inviato ? 'checked' : '' }}>
                Inviato
            </label>

            <br>

            <label>
                <input type="checkbox"
                       class="chk-co"
                       name="co_ricevuta"
                       value="1"
                       {{ $riga->co_ricevuta ? 'checked' : '' }}>
                Conferma ordine ricevuta
            </label>

            <br>

            <label>
                <input type="checkbox"
                       class="chk-produzione"
                       name="in_produzione"
                       value="1"
                       {{ $riga->in_produzione ? 'checked' : '' }}>
                In produzione
            </label>

            <br><br>

            @if($riga->pdf_path)
                <a href="{{ asset('storage/' . $riga->pdf_path) }}" target="_blank">
                    Apri PDF
                </a>
                <br>
            @endif

            <input type="file"
                   name="pdf"
                   accept="application/pdf">

            <br><br>

            <button type="submit" class="btn btn-azione">
                Salva
            </button>

        </form>

    @elseif($ordine->stato == 'completo_attesa_merce')

        <form id="form_riga_{{ $riga->id }}"
              method="POST"
              action="/righe-ordine/{{ $riga->id }}/aggiorna"
              enctype="multipart/form-data"
              onsubmit="return confermaAvanzamentoRiga(this)">

            @csrf

            <label>
                <input type="checkbox"
                       class="chk-merce"
                       name="merce_arrivata"
                       value="1"
                       {{ $riga->merce_arrivata ? 'checked' : '' }}>
                Merce arrivata
            </label>

            <br><br>

            @if($riga->pdf_path)
                <a href="{{ asset('storage/' . $riga->pdf_path) }}" target="_blank">
                    Apri PDF
                </a>
                <br>
            @endif

            <input type="file"
                   name="pdf"
                   accept="application/pdf">

            <br><br>

            <button type="submit" class="btn btn-azione">
                Salva
            </button>

        </form>

    @else

        @if($riga->pdf_path)
            <a href="{{ asset('storage/' . $riga->pdf_path) }}" target="_blank">
                Apri PDF
            </a>
        @else
            -
        @endif

    @endif

</td>

            </tr>

        @endforeach

    </table>
@if(
    $ordine->stato == 'preparazione_contratto' ||
    $ordine->stato == 'attesa_saldo_merce' ||
    $ordine->stato == 'programmare_posa' ||
    $ordine->stato == 'concluso'
)

    <br>

    <h2>Stato avanzato ordine</h2>

    <form id="form_stato_avanzato"
          method="POST"
          action="/ordini/{{ $ordine->id }}/aggiorna-stato-avanzato"
          onsubmit="return confermaAvanzamentoAvanzato(this)">

        @csrf

        @if($ordine->stato == 'preparazione_contratto')

            <p>
                <label>
                    <input type="checkbox"
                           id="rilievo_effettuato"
                           name="rilievo_effettuato"
                           value="1"
                           {{ $ordine->rilievo_effettuato ? 'checked' : '' }}>

                    Rilievo effettuato
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                           id="contratto_firmato"
                           name="contratto_firmato"
                           value="1"
                           {{ $ordine->contratto_firmato ? 'checked' : '' }}>

                    Contratto firmato dal cliente
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                           id="acconto_versato"
                           name="acconto_versato"
                           value="1"
                           {{ $ordine->acconto_versato ? 'checked' : '' }}>

                    Acconto versato
                </label>
            </p>

        @endif

        @if($ordine->stato == 'attesa_saldo_merce')

            <p>
                <label>
                    <input type="checkbox"
                           id="saldo_merce_ricevuto"
                           name="saldo_merce_ricevuto"
                           value="1"
                           {{ $ordine->saldo_merce_ricevuto ? 'checked' : '' }}>

                    Saldo merce ricevuto
                </label>
            </p>

        @endif

        @if($ordine->stato == 'programmare_posa')

            <p>
                <label>
                    <input type="checkbox"
                           id="posa_effettuata"
                           name="posa_effettuata"
                           value="1"
                           {{ $ordine->posa_effettuata ? 'checked' : '' }}>

                    Posa effettuata
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                           id="fattura_saldo_posa_fatta"
                           name="fattura_saldo_posa_fatta"
                           value="1"
                           {{ $ordine->fattura_saldo_posa_fatta ? 'checked' : '' }}>

                    Fattura saldo posa fatta
                </label>
            </p>

        @endif

        @if($ordine->stato == 'concluso')

            <p>
                <label>
                    <input type="checkbox"
                           id="saldo_finale_ricevuto"
                           name="saldo_finale_ricevuto"
                           value="1"
                           {{ $ordine->saldo_finale_ricevuto ? 'checked' : '' }}>

                    Saldo finale ricevuto dal cliente
                </label>
            </p>

            @if($ordine->commessa && $ordine->commessa->pratica_enea)

                <p>
                    <label>
                        <input type="checkbox"
                               id="invio_enea_effettuato"
                               name="invio_enea_effettuato"
                               value="1"
                               {{ $ordine->invio_enea_effettuato ? 'checked' : '' }}>

                        Invio ENEA effettuato
                    </label>
                </p>

            @endif

        @endif

        <button type="submit" class="btn btn-azione">
            Salva stato avanzato
        </button>

    </form>

@endif


</div>

<script>

    let statoOrdine = "{{ $ordine->stato }}";


function caricaProdottiNuovo(){

    let fornitoreId = document.getElementById('fornitore_select_nuovo').value;
    let select = document.getElementById('prodotto_select_nuovo');

    for(let i = 0; i < select.options.length; i++){

        let option = select.options[i];

        if(option.value === ''){
            option.style.display = 'block';
            continue;
        }

        if(option.dataset.fornitore === fornitoreId){
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    }

    select.value = '';
}

function compilaProdottoNuovo(){

    let option = document.getElementById('prodotto_select_nuovo').selectedOptions[0];

    if(!option || option.value === ''){
        return;
    }

    document.getElementById('descrizione_nuovo').value = option.dataset.descrizione;

    document.getElementById('input_prezzo_nuovo').value = option.dataset.listino;

    document.getElementById('s1_nuovo').value = option.dataset.s1;
    document.getElementById('s2_nuovo').value = option.dataset.s2;
    document.getElementById('s3_nuovo').value = option.dataset.s3;

    document.getElementById('bene_nuovo').checked =
        option.dataset.bene == 1;
}

function cambiaPrezzoNuovo(){

    let radio = document.querySelector('input[name="modalita_calcolo"]:checked');

    let input = document.getElementById('input_prezzo_nuovo');
    let label = document.getElementById('label_prezzo_nuovo');

    if(radio.value === 'da_listino'){

        input.name = 'prezzo_listino';
        label.innerHTML = 'Prezzo listino';

    } else {

        input.name = 'costo_netto';
        label.innerHTML = 'Prezzo scontato';
    }
}

function apriModificaRigaOrdine(id){
    document.getElementById('edit_riga_ordine_' + id).style.display = 'table-row';
}

function apriModificaServizioOrdine(id){
    document.getElementById('edit_servizio_ordine_' + id).style.display = 'block';
}

function confermaRitornoStato() {
    return confirm(
        'L ordine verrà riportato allo stato precedente. Confermi?'
    );
}
function tutteSpuntate(selector) {
    let elementi = document.querySelectorAll(selector);

    if (elementi.length === 0) {
        return false;
    }

    for (let i = 0; i < elementi.length; i++) {
        if (!elementi[i].checked) {
            return false;
        }
    }

    return true;
}

function confermaAvanzamentoRiga(form) {

    if (statoOrdine === 'in_lavorazione') {
        let tutteInviato = tutteSpuntate('.chk-inviato');
        let tutteCo = tutteSpuntate('.chk-co');
        let tutteProduzione = tutteSpuntate('.chk-produzione');

        if (tutteInviato && tutteCo && tutteProduzione) {
            return confirm(
                'Tutte le righe risultano complete.\n\nL ordine verrà spostato in: Completo - attesa merce.\n\nConfermi?'
            );
        }
    }

    if (statoOrdine === 'completo_attesa_merce') {
        let tuttaMerceArrivata = tutteSpuntate('.chk-merce');

        if (tuttaMerceArrivata) {
            return confirm(
                'Tutta la merce risulta arrivata.\n\nL ordine verrà spostato in: Attesa saldo merce.\n\nConfermi?'
            );
        }
    }

    return true;
}


</script>


