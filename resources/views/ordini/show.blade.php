@include('partials.menu')

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<h1>Dettaglio Ordine</h1>

<p><strong>Numero ordine:</strong> {{ $ordine->numero }}</p>
<p><strong>Preventivo origine:</strong> {{ $ordine->preventivo->numero ?? '' }}</p>

<p>
    <strong>Cliente:</strong>
    {{ optional(optional($ordine->commessa)->cliente)->nome }}
    {{ optional(optional($ordine->commessa)->cliente)->cognome }}
</p>

<p>
    <strong>Costo netto prodotti a noi:</strong>
    {{ number_format($ordine->imponibile, 2, ',', '.') }} €
</p>

<p><strong>Stato:</strong> {{ $ordine->stato }}</p>

<hr>

<h2>Righe Ordine</h2>

<table border="1" cellpadding="5" width="100%">
<tr>
<th>Prodotto</th>
<th>Fornitore</th>
<th>Quantità</th>
<th>Costo</th>
<th>Stati</th>
<th>Salva</th>
</tr>

@foreach($ordine->righe as $riga)
<tr>
<form method="POST" action="/righe-ordine/{{ $riga->id }}/aggiorna" onsubmit="return confermaCambioStato(this)">
@csrf

<td>{{ $riga->descrizione }}</td>
<td>{{ $riga->fornitore->ragione_sociale ?? '' }}</td>
<td>{{ $riga->quantita }}</td>
<td>{{ number_format($riga->imponibile,2,',','.') }} €</td>

<td>

@if($ordine->stato == 'in_lavorazione')

<label>
<input type="checkbox" name="inviato" {{ $riga->inviato ? 'checked' : '' }}>
Inviato
</label><br>

<label>
<input type="checkbox" name="co_ricevuta" {{ $riga->co_ricevuta ? 'checked' : '' }}>
CO ricevuta
</label><br>

<label>
<input type="checkbox" name="in_produzione" {{ $riga->in_produzione ? 'checked' : '' }}>
Produzione
</label>

@elseif($ordine->stato == 'completo_attesa_merce')

<label>
<input type="checkbox" name="merce_arrivata" {{ $riga->merce_arrivata ? 'checked' : '' }}>
Merce arrivata
</label>

@else

✔ completato

@endif

</td>

<td>
@if($ordine->stato == 'in_lavorazione' || $ordine->stato == 'completo_attesa_merce')
<button>Salva</button>
@endif
</td>

</form>
</tr>
@endforeach

</table>

@if($ordine->stato == 'attesa_saldo_merce')

<hr>
<form method="POST" action="/ordini/{{ $ordine->id }}/aggiorna-stato-avanzato" onsubmit="return confermaCambioStato(this)">
@csrf

<label>
<input type="checkbox" name="saldo_merce_ricevuto" {{ $ordine->saldo_merce_ricevuto ? 'checked' : '' }}>
Saldo ricevuto
</label>

<br><br>
<button>Salva</button>

</form>

@endif

@if($ordine->stato == 'programmare_posa')

<hr>
<form method="POST" action="/ordini/{{ $ordine->id }}/aggiorna-stato-avanzato" onsubmit="return confermaCambioStato(this)">
@csrf

<label>
<input type="checkbox" name="posa_effettuata" {{ $ordine->posa_effettuata ? 'checked' : '' }}>
Posa fatta
</label><br>

<label>
<input type="checkbox" name="fattura_saldo_posa_fatta" {{ $ordine->fattura_saldo_posa_fatta ? 'checked' : '' }}>
Fattura saldo fatta
</label>

<br><br>
<button>Salva</button>

</form>

@endif

<script>
function confermaCambioStato(form) {

    let stato = "{{ $ordine->stato }}";

    // -------------------------
    // IN LAVORAZIONE → COMPLETO
    // -------------------------
    if (stato === "in_lavorazione") {

        let tutteOk = true;

        document.querySelectorAll('input[name="inviato"]').forEach(i => { if(!i.checked) tutteOk = false; });
        document.querySelectorAll('input[name="co_ricevuta"]').forEach(i => { if(!i.checked) tutteOk = false; });
        document.querySelectorAll('input[name="in_produzione"]').forEach(i => { if(!i.checked) tutteOk = false; });

        if (tutteOk) {
            if (!confirm("Ordine passerà a: COMPLETO - ATTESA MERCE. Continuare?")) {
                form.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
                return false;
            }
        }
    }

    // -------------------------
    // MERCE → SALDO
    // -------------------------
    if (stato === "completo_attesa_merce") {

        let tutteOk = true;

        document.querySelectorAll('input[name="merce_arrivata"]').forEach(i => { if(!i.checked) tutteOk = false; });

        if (tutteOk) {
            if (!confirm("Ordine passerà a: ATTESA SALDO MERCE. Continuare?")) {
                form.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
                return false;
            }
        }
    }

    // -------------------------
    // SALDO → POSA
    // -------------------------
    if (stato === "attesa_saldo_merce") {

        let saldo = form.querySelector('input[name="saldo_merce_ricevuto"]');

        if (saldo && saldo.checked) {
            if (!confirm("Ordine passerà a: PROGRAMMARE POSA. Continuare?")) {
                saldo.checked = false;
                return false;
            }
        }
    }

    // -------------------------
    // POSA → CONCLUSO
    // -------------------------
    if (stato === "programmare_posa") {

        let posa = form.querySelector('input[name="posa_effettuata"]');
        let fattura = form.querySelector('input[name="fattura_saldo_posa_fatta"]');

        if (posa && fattura && posa.checked && fattura.checked) {
            if (!confirm("Ordine verrà CONCLUSO. Continuare?")) {
                posa.checked = false;
                fattura.checked = false;
                return false;
            }
        }
    }

    return true;
}
</script>