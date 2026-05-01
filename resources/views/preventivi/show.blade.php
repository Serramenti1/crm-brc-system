@include('partials.menu')

<h2>Prodotti</h2>

<!-- AGGIUNTA PRODOTTO -->
<details>
<summary><strong>+ Aggiungi prodotto</strong></summary>

<form method="POST" action="/preventivi/{{ $preventivo->id }}/aggiungi-riga-prodotto">
@csrf

<p>Prodotto da listino fornitore<br>
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
>
    {{ $prodotto->fornitore->ragione_sociale }} - {{ $prodotto->descrizione }}
</option>
@endforeach

</select>
</p>

<input type="hidden" id="fornitore_id" name="fornitore_id">

<p>Descrizione<br>
<input type="text" id="descrizione" name="descrizione" required>
</p>

<p>Quantità<br>
<input type="number" name="quantita" value="1" step="0.01">
</p>

<p>Tipo prezzo<br>
<label>
<input type="radio" name="modalita_calcolo" value="da_listino" checked onclick="cambiaPrezzo()"> Listino
</label>

<label>
<input type="radio" name="modalita_calcolo" value="da_costo_netto" onclick="cambiaPrezzo()"> Scontato
</label>
</p>

<p id="label_prezzo">Prezzo listino</p>
<input type="number" id="input_prezzo" step="0.01" name="prezzo_listino">

<p>Sconto 1 %<br>
<input type="number" id="sconto_fornitore_1" name="sconto_fornitore_1" value="0">
</p>

<p>Sconto 2 %<br>
<input type="number" id="sconto_fornitore_2" name="sconto_fornitore_2" value="0">
</p>

<p>Sconto 3 %<br>
<input type="number" id="sconto_fornitore_3" name="sconto_fornitore_3" value="0">
</p>

<p>Ricarico cliente %<br>
<input type="number" name="ricarico_percentuale" value="0">
</p>

<button>Salva</button>

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
Qta: {{ $riga->quantita }}
</td>

<td>
Listino: {{ number_format($riga->prezzo_listino,2,',','.') }} €<br>
Scontato: {{ number_format($riga->costo_netto,2,',','.') }} €<br>
Cliente: {{ number_format($riga->prezzo_cliente_unitario,2,',','.') }} €<br>
Sconto applicato: {{ number_format($riga->sconto_cliente_percentuale,2,',','.') }}%
</td>

<td>

@foreach($riga->servizi as $servizio)

<div style="border-bottom:1px solid #ccc; margin-bottom:5px; padding-bottom:5px;">

<strong>{{ $servizio->tipo_servizio }}</strong>

- € {{ number_format($servizio->prezzo_cliente,2,',','.') }}

( x {{ $riga->quantita }} )

= € {{ number_format($servizio->prezzo_cliente * $riga->quantita,2,',','.') }}

<form action="/servizi-riga/{{ $servizio->id }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')
<button>X</button>
</form>

<button type="button" onclick="apriModificaServizio({{ $servizio->id }})">Modifica</button>

<div id="edit_servizio_{{ $servizio->id }}" style="display:none; margin-top:10px; border:1px solid #ccc; padding:10px;">

<form method="POST" action="/servizi-riga/{{ $servizio->id }}">
@csrf
@method('PUT')

<p>Tipo<br>
<select name="tipo_servizio">
<option value="posa" {{ $servizio->tipo_servizio=='posa'?'selected':'' }}>Posa</option>
<option value="trasporto" {{ $servizio->tipo_servizio=='trasporto'?'selected':'' }}>Trasporto</option>
<option value="smaltimento" {{ $servizio->tipo_servizio=='smaltimento'?'selected':'' }}>Smaltimento</option>
</select>
</p>

<p>Costo<br>
<input type="number" name="costo_brc" value="{{ $servizio->costo_brc }}" step="0.01">
</p>

<p>Ricarico %<br>
<input type="number" name="ricarico_percentuale" value="{{ $servizio->ricarico_percentuale }}" step="0.01">
</p>

<button>Salva</button>
<button type="button" onclick="chiudiModificaServizio({{ $servizio->id }})">Annulla</button>

</form>

</div>

</div>

@endforeach

<details>
<summary>+ Servizio</summary>

<form method="POST" action="/righe-prodotti/{{ $riga->id }}/servizi">
@csrf

<select name="tipo_servizio">
<option value="posa">Posa</option>
<option value="trasporto">Trasporto</option>
<option value="smaltimento">Smaltimento</option>
</select>

<input type="number" name="costo_brc" placeholder="Costo">
<input type="number" name="ricarico_percentuale" placeholder="Ricarico %">

<button>OK</button>

</form>
</details>

</td>

<td>

<button type="button" onclick="apriModificaRiga({{ $riga->id }})">Modifica</button>

<form action="/righe-preventivo-prodotti/{{ $riga->id }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')
<button>Elimina</button>
</form>

<div id="edit_riga_{{ $riga->id }}" style="display:none; margin-top:10px; border:1px solid #ccc; padding:10px;">

<form method="POST" action="/righe-preventivo-prodotti/{{ $riga->id }}">
@csrf
@method('PUT')

<p>Descrizione<br>
<input type="text" name="descrizione" value="{{ $riga->descrizione }}" required>
</p>

<p>Quantità<br>
<input type="number" name="quantita" value="{{ $riga->quantita }}" step="0.01">
</p>

<input type="hidden" name="modalita_calcolo" value="{{ $riga->modalita_calcolo }}">

<p>Prezzo listino<br>
<input type="number" name="prezzo_listino" value="{{ $riga->prezzo_listino }}" step="0.01">
</p>

<p>Prezzo scontato<br>
<input type="number" name="costo_netto" value="{{ $riga->costo_netto }}" step="0.01">
</p>

<p>Sconto 1<br>
<input type="number" name="sconto_fornitore_1" value="{{ $riga->sconto_fornitore_1 }}">
</p>

<p>Sconto 2<br>
<input type="number" name="sconto_fornitore_2" value="{{ $riga->sconto_fornitore_2 }}">
</p>

<p>Sconto 3<br>
<input type="number" name="sconto_fornitore_3" value="{{ $riga->sconto_fornitore_3 }}">
</p>

<p>Ricarico<br>
<input type="number" name="ricarico_percentuale" value="{{ $riga->ricarico_percentuale }}">
</p>

<button>Salva modifica</button>
<button type="button" onclick="chiudiModificaRiga({{ $riga->id }})">Annulla</button>

</form>

</div>

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
}

function apriModificaRiga(id){
    document.getElementById('edit_riga_' + id).style.display = 'block';
}

function chiudiModificaRiga(id){
    document.getElementById('edit_riga_' + id).style.display = 'none';
}

function apriModificaServizio(id){
    document.getElementById('edit_servizio_' + id).style.display = 'block';
}

function chiudiModificaServizio(id){
    document.getElementById('edit_servizio_' + id).style.display = 'none';
}
</script>