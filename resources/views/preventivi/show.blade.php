@include('partials.menu')

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<div style="margin-bottom:15px;">
    <a href="/preventivi">
        ← Torna ai preventivi
    </a>
</div>

<h2>Prodotti</h2>

<details>
<summary><strong>+ Aggiungi prodotto</strong></summary>

<form method="POST" action="/preventivi/{{ $preventivo->id }}/aggiungi-riga-prodotto">
@csrf

<p>
Prodotto da listino fornitore<br>

<select id="prodotto_fornitore_select" onchange="compilaProdottoFornitore()">

<option value="">-- Seleziona prodotto fornitore --</option>

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
>

    {{ $prodotto->fornitore->ragione_sociale }}
    -
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

<label>
<input type="radio"
       name="modalita_calcolo"
       value="da_listino"
       checked
       onclick="cambiaPrezzo()">

Listino
</label>

<label>
<input type="radio"
       name="modalita_calcolo"
       value="da_costo_netto"
       onclick="cambiaPrezzo()">

Scontato
</label>

</p>

<p id="label_prezzo">
Prezzo listino
</p>

<input type="number"
       id="input_prezzo"
       step="0.01"
       name="prezzo_listino">

<p>
Sconto 1 %<br>
<input type="number"
       id="sconto_fornitore_1"
       name="sconto_fornitore_1"
       value="0">
</p>

<p>
Sconto 2 %<br>
<input type="number"
       id="sconto_fornitore_2"
       name="sconto_fornitore_2"
       value="0">
</p>

<p>
Sconto 3 %<br>
<input type="number"
       id="sconto_fornitore_3"
       name="sconto_fornitore_3"
       value="0">
</p>

<p>
Ricarico cliente %<br>
<input type="number"
       name="ricarico_percentuale"
       value="0">
</p>

<p>
<label>

<input type="checkbox"
       id="bene_significativo"
       name="bene_significativo"
       value="1">

Bene significativo

</label>
</p>

<button>
Salva
</button>

</form>

</details>

<br>

<table border="1" cellpadding="5" width="100%">

<tr>
    <th>Prodotto</th>
    <th>Prezzi</th>
    <th>Servizi</th>
    <th>Azioni</th>
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

<td>

@foreach($riga->servizi as $servizio)

<div style="border-bottom:1px solid #ccc; margin-bottom:5px; padding-bottom:5px;">

<strong>{{ $servizio->tipo_servizio }}</strong>

-

€ {{ number_format($servizio->prezzo_cliente,2,',','.') }}

( x {{ $riga->quantita }} )

=

€ {{ number_format($servizio->prezzo_cliente * $riga->quantita,2,',','.') }}

<form action="/servizi-riga/{{ $servizio->id }}"
      method="POST"
      style="display:inline;">

@csrf
@method('DELETE')

<button>X</button>

</form>

<button type="button"
        onclick="apriModificaServizio({{ $servizio->id }})">

Modifica

</button>

<div id="edit_servizio_{{ $servizio->id }}"
     style="display:none; margin-top:10px; border:1px solid #ccc; padding:10px;">

<form method="POST" action="/servizi-riga/{{ $servizio->id }}">

@csrf
@method('PUT')

<p>
Tipo<br>

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
Costo<br>

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

<button>Salva</button>

<button type="button"
        onclick="chiudiModificaServizio({{ $servizio->id }})">

Annulla

</button>

</form>

</div>

</div>

@endforeach

<details>

<summary>+ Servizio</summary>

<form method="POST"
      action="/righe-prodotti/{{ $riga->id }}/servizi">

@csrf

<p>
Servizio da impostazioni<br>

<select id="servizio_extra_{{ $riga->id }}"
        onchange="compilaServizioExtra({{ $riga->id }})">

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

<input type="text"
       id="tipo_servizio_{{ $riga->id }}"
       name="tipo_servizio"
       required>

</p>

<p>
Descrizione<br>

<input type="text"
       id="descrizione_servizio_{{ $riga->id }}"
       name="descrizione">

</p>

<p>
Costo BRC<br>

<input type="number"
       id="costo_brc_{{ $riga->id }}"
       name="costo_brc"
       step="0.01">

</p>

<p>
Ricarico %<br>

<input type="number"
       id="ricarico_servizio_{{ $riga->id }}"
       name="ricarico_percentuale"
       step="0.01">

</p>

<button>OK</button>

</form>

</details>

</td>

<td>

<button type="button"
        onclick="apriModificaRiga({{ $riga->id }})">

Modifica

</button>

<form action="/righe-preventivo-prodotti/{{ $riga->id }}"
      method="POST"
      style="display:inline;">

@csrf
@method('DELETE')

<button>Elimina</button>

</form>

</td>

</tr>

@endforeach

</table>

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

function compilaProdottoFornitore(){

    let select = document.getElementById('prodotto_fornitore_select');
    let option = select.options[select.selectedIndex];

    if(option.value === ''){
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