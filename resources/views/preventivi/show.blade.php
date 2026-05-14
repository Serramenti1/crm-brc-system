@include('partials.menu')

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<div class="container">

    <div style="margin-bottom:15px;">
        <a href="/preventivi" class="btn btn-azione">
            ← Torna ai preventivi
        </a>
    </div>

    <h2>Prodotti</h2>

    <details>
        <summary>
            <strong>+ Aggiungi prodotto</strong>
        </summary>

        <form method="POST" action="/preventivi/{{ $preventivo->id }}/aggiungi-riga-prodotto">
            @csrf

            <p>
                Fornitore<br>

                <select id="fornitore_select" onchange="filtraProdottiPerFornitore()">
                    <option value="">-- Seleziona fornitore --</option>

                    @foreach($fornitori as $fornitore)
                        <option value="{{ $fornitore->id }}">
                            {{ $fornitore->ragione_sociale }}
                        </option>
                    @endforeach
                </select>
            </p>

            <p>
                Prodotto da listino fornitore<br>

                <select id="prodotto_fornitore_select" onchange="compilaProdottoFornitore()" disabled>
                    <option value="">-- Prima seleziona un fornitore --</option>

                    @foreach($prodottiFornitore as $prodotto)
                        <option
                            value="{{ $prodotto->id }}"
                            data-fornitore="{{ $prodotto->fornitore_id }}"
                            data-descrizione="{{ $prodotto->descrizione }}"
                            data-listino="{{ $prodotto->prezzo_listino }}"
                            data-sconto1="{{ $prodotto->sconto_1 }}"
                            data-sconto2="{{ $prodotto->sconto_2 }}"
                            data-sconto3="{{ $prodotto->sconto_3 }}"
                            data-bene-significativo="{{ $prodotto->bene_significativo ? 1 : 0 }}"
                            style="display:none;"
                        >
                            {{ $prodotto->descrizione }}
                        </option>
                    @endforeach
                </select>
            </p>

            <input type="hidden" id="fornitore_id" name="fornitore_id">

            <p>
                Descrizione<br>
                <input type="text" id="descrizione" name="descrizione" required>
            </p>

            <p>
                Quantità<br>
                <input type="number" name="quantita" value="1" step="0.01">
            </p>

            <p>
                Tipo prezzo<br>

                <div style="display:flex; gap:25px; align-items:center;">

                    <label style="display:flex; align-items:center; gap:8px; width:auto;">

                        <input
                            type="radio"
                            name="modalita_calcolo"
                            value="da_listino"
                            checked
                            onclick="cambiaPrezzo()"
                        >

                        Listino

                    </label>

                    <label style="display:flex; align-items:center; gap:8px; width:auto;">

                        <input
                            type="radio"
                            name="modalita_calcolo"
                            value="da_costo_netto"
                            onclick="cambiaPrezzo()"
                        >

                        Scontato

                    </label>

                </div>
            </p>

            <p id="label_prezzo">
                Prezzo listino
            </p>

            <input
                type="number"
                id="input_prezzo"
                step="0.01"
                name="prezzo_listino"
            >

            <p>
                Sconto 1 %<br>
                <input type="number" id="sconto_fornitore_1" name="sconto_fornitore_1" value="0">
            </p>

            <p>
                Sconto 2 %<br>
                <input type="number" id="sconto_fornitore_2" name="sconto_fornitore_2" value="0">
            </p>

            <p>
                Sconto 3 %<br>
                <input type="number" id="sconto_fornitore_3" name="sconto_fornitore_3" value="0">
            </p>

            <p>
                Ricarico cliente %<br>
                <input
                    type="number"
                    name="ricarico_percentuale"
                    value="{{ $impostazioni->ricarico_prodotti_default ?? 50 }}"
                    step="0.01"
                >
            </p>

            <p>
                <label>
                    <input
                        type="checkbox"
                        id="bene_significativo"
                        name="bene_significativo"
                        value="1"
                    >
                    Bene significativo
                </label>
            </p>

            <button type="submit" class="btn btn-azione">
                Salva
            </button>
        </form>
    </details>

    <br>

    <table class="tabella-lista">

        <tr>
            <th>Prodotto</th>
            <th>Prezzi</th>
            <th>Azioni</th>
            <th>Servizi</th>
        </tr>

        @foreach($preventivo->righeProdotti as $riga)

            <tr>

                <td>
                    <strong>{{ $riga->descrizione }}</strong><br>

                    Qta: {{ $riga->quantita }}<br>

                    Bene significativo:
                    {{ $riga->bene_significativo ? 'Sì' : 'No' }}
                </td>

                <td>
                    Listino:
                    {{ number_format($riga->prezzo_listino,2,',','.') }} €<br>

                    Scontato:
                    {{ number_format($riga->costo_netto,2,',','.') }} €<br>

                    Cliente:
                    {{ number_format($riga->prezzo_cliente_unitario,2,',','.') }} €<br>

                    Sconto applicato:
                    {{ number_format($riga->sconto_cliente_percentuale,2,',','.') }}%
                </td>

                <td class="azioni">
                    <div class="azioni-bottoni">

                        <a
                            href="/righe-preventivo-prodotti/{{ $riga->id }}/edit"
                            class="btn btn-azione"
                        >
                            Modifica
                        </a>

                        <form
                            action="/righe-preventivo-prodotti/{{ $riga->id }}"
                            method="POST"
                            class="form-elimina"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-elimina"
                                onclick="return confirm('Eliminare questa riga prodotto?')"
                            >
                                🗑️
                            </button>
                        </form>

                    </div>
                </td>

                <td>

                    @foreach($riga->servizi as $servizio)

                        <div style="border-bottom:1px solid #ccc; margin-bottom:10px; padding-bottom:10px;">

                            <div style="display:flex; justify-content:space-between; align-items:center; gap:15px;">

                                <div>
                                    <strong>{{ $servizio->tipo_servizio }}</strong>
                                    -
                                    € {{ number_format($servizio->prezzo_cliente,2,',','.') }}
                                    ( x {{ $riga->quantita }} )
                                    =
                                    € {{ number_format($servizio->prezzo_cliente * $riga->quantita,2,',','.') }}
                                </div>

                                <div style="display:flex; gap:8px; align-items:center; flex-shrink:0;">

                                    <form
                                        action="/servizi-riga/{{ $servizio->id }}"
                                        method="POST"
                                        class="form-elimina"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-elimina"
                                            onclick="return confirm('Eliminare questo servizio?')"
                                        >
                                            🗑️
                                        </button>
                                    </form>

                                    <button
                                        type="button"
                                        class="btn btn-azione"
                                        onclick="apriModificaServizio({{ $servizio->id }})"
                                    >
                                        Modifica
                                    </button>

                                </div>

                            </div>

                            <div
                                id="edit_servizio_{{ $servizio->id }}"
                                style="display:none; margin-top:10px; border:1px solid #ccc; padding:10px;"
                            >

                                <form method="POST" action="/servizi-riga/{{ $servizio->id }}">
                                    @csrf
                                    @method('PUT')

                                    <p>
                                        Tipo<br>
                                        <input
                                            type="text"
                                            name="tipo_servizio"
                                            value="{{ $servizio->tipo_servizio }}"
                                        >
                                    </p>

                                    <p>
                                        Descrizione<br>
                                        <input
                                            type="text"
                                            name="descrizione"
                                            value="{{ $servizio->descrizione }}"
                                        >
                                    </p>

                                    <p>
                                        Costo<br>
                                        <input
                                            type="number"
                                            name="costo_brc"
                                            value="{{ $servizio->costo_brc }}"
                                            step="0.01"
                                        >
                                    </p>

                                    <p>
                                        Ricarico %<br>
                                        <input
                                            type="number"
                                            name="ricarico_percentuale"
                                            value="{{ $servizio->ricarico_percentuale }}"
                                            step="0.01"
                                        >
                                    </p>

                                    <button type="submit" class="btn btn-azione">
                                        Salva
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-azione"
                                        onclick="chiudiModificaServizio({{ $servizio->id }})"
                                    >
                                        Annulla
                                    </button>
                                </form>

                            </div>

                        </div>

                    @endforeach

                    <details>
                        <summary>
                            <strong>+ Servizio</strong>
                        </summary>

                        <form
                            method="POST"
                            action="/righe-prodotti/{{ $riga->id }}/servizi"
                            style="margin-top:10px;"
                        >
                            @csrf

                            <p>
                                Servizio da impostazioni<br>

                                <select
                                    id="servizio_extra_{{ $riga->id }}"
                                    onchange="compilaServizioExtra({{ $riga->id }})"
                                >
                                    <option value="">
                                        -- Seleziona servizio extra --
                                    </option>

                                    @foreach($serviziExtra as $servizioExtra)
                                        <option
                                            value="{{ $servizioExtra->id }}"
                                            data-nome="{{ $servizioExtra->nome }}"
                                            data-costo="{{ $servizioExtra->costo_brc }}"
                                            data-ricarico="{{ $servizioExtra->ricarico_percentuale }}"
                                        >
                                            {{ $servizioExtra->nome }}
                                            -
                                            costo {{ number_format($servizioExtra->costo_brc,2,',','.') }} €
                                            -
                                            ricarico {{ number_format($servizioExtra->ricarico_percentuale,2,',','.') }}%
                                        </option>
                                    @endforeach
                                </select>
                            </p>

                            <p>
                                Tipo servizio<br>
                                <input
                                    type="text"
                                    id="tipo_servizio_{{ $riga->id }}"
                                    name="tipo_servizio"
                                    required
                                >
                            </p>

                            <p>
                                Descrizione<br>
                                <input
                                    type="text"
                                    id="descrizione_servizio_{{ $riga->id }}"
                                    name="descrizione"
                                >
                            </p>

                            <p>
                                Costo BRC<br>
                                <input
                                    type="number"
                                    id="costo_brc_{{ $riga->id }}"
                                    name="costo_brc"
                                    step="0.01"
                                >
                            </p>

                            <p>
                                Ricarico %<br>
                                <input
                                    type="number"
                                    id="ricarico_servizio_{{ $riga->id }}"
                                    name="ricarico_percentuale"
                                    step="0.01"
                                >
                            </p>

                            <button type="submit" class="btn btn-azione">
                                Salva servizio
                            </button>
                        </form>

                    </details>

                </td>

            </tr>

        @endforeach

    </table>

</div>

<script>
function cambiaPrezzo(){

    let tipo = document.querySelector('input[name="modalita_calcolo"]:checked').value;

    let input = document.getElementById('input_prezzo');
    let label = document.getElementById('label_prezzo');

    if(tipo === 'da_listino'){
        input.name = 'prezzo_listino';
        label.innerHTML = 'Prezzo listino';
    } else {
        input.name = 'costo_netto';
        label.innerHTML = 'Prezzo scontato';
    }
}

function filtraProdottiPerFornitore(){

    let fornitoreSelect = document.getElementById('fornitore_select');
    let prodottoSelect = document.getElementById('prodotto_fornitore_select');

    let fornitoreId = fornitoreSelect.value;

    prodottoSelect.innerHTML = '';

    if(fornitoreId === ''){

        let optionVuota = document.createElement('option');
        optionVuota.value = '';
        optionVuota.text = '-- Prima seleziona un fornitore --';

        prodottoSelect.appendChild(optionVuota);
        prodottoSelect.disabled = true;

        document.getElementById('fornitore_id').value = '';
        document.getElementById('descrizione').value = '';
        document.getElementById('input_prezzo').value = '';
        document.getElementById('sconto_fornitore_1').value = 0;
        document.getElementById('sconto_fornitore_2').value = 0;
        document.getElementById('sconto_fornitore_3').value = 0;
        document.getElementById('bene_significativo').checked = false;

        return;
    }

    let optionDefault = document.createElement('option');
    optionDefault.value = '';
    optionDefault.text = '-- Seleziona prodotto --';

    prodottoSelect.appendChild(optionDefault);

    let prodotti = @json($prodottiFornitore);

    prodotti.forEach(function(prodotto){

        if(prodotto.fornitore_id == fornitoreId){

            let option = document.createElement('option');

            option.value = prodotto.id;
            option.text = prodotto.descrizione;

            option.dataset.fornitore = prodotto.fornitore_id;
            option.dataset.descrizione = prodotto.descrizione;
            option.dataset.listino = prodotto.prezzo_listino;
            option.dataset.sconto1 = prodotto.sconto_1;
            option.dataset.sconto2 = prodotto.sconto_2;
            option.dataset.sconto3 = prodotto.sconto_3;
            option.dataset.beneSignificativo = prodotto.bene_significativo ? 1 : 0;

            prodottoSelect.appendChild(option);
        }

    });

    prodottoSelect.disabled = false;
}

function compilaProdottoFornitore(){

    let select = document.getElementById('prodotto_fornitore_select');
    let option = select.options[select.selectedIndex];

    if(!option || option.value === ''){
        return;
    }

    document.getElementById('fornitore_id').value = option.dataset.fornitore;
    document.getElementById('descrizione').value = option.dataset.descrizione;

    let radioListino = document.querySelector('input[name="modalita_calcolo"][value="da_listino"]');

    radioListino.checked = true;

    cambiaPrezzo();

    document.getElementById('input_prezzo').value = option.dataset.listino;
    document.getElementById('sconto_fornitore_1').value = option.dataset.sconto1;
    document.getElementById('sconto_fornitore_2').value = option.dataset.sconto2;
    document.getElementById('sconto_fornitore_3').value = option.dataset.sconto3;

    document.getElementById('bene_significativo').checked =
        option.dataset.beneSignificativo == 1;
}

function apriModificaServizio(id){
    document.getElementById('edit_servizio_' + id).style.display = 'block';
}

function chiudiModificaServizio(id){
    document.getElementById('edit_servizio_' + id).style.display = 'none';
}

function compilaServizioExtra(rigaId){

    let select = document.getElementById('servizio_extra_' + rigaId);
    let option = select.options[select.selectedIndex];

    if(option.value === ''){
        return;
    }

    document.getElementById('tipo_servizio_' + rigaId).value =
        option.dataset.nome;

    document.getElementById('descrizione_servizio_' + rigaId).value =
        option.dataset.nome;

    document.getElementById('costo_brc_' + rigaId).value =
        option.dataset.costo;

    document.getElementById('ricarico_servizio_' + rigaId).value =
        option.dataset.ricarico;
}
</script>