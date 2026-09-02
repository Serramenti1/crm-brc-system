<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=1002">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>

<div class="topbar" style="display:flex; justify-content:space-between; align-items:center;">
    <span>CRM BRC SYSTEM</span>
    <span style="font-size:12px; font-weight:normal; opacity:0.8;">
        {{ config('app.versione_crm') }}
    </span>
</div>

{{-- ===================================================== --}}
{{-- RILEVAMENTO SEZIONE ATTIVA --}}
{{-- ===================================================== --}}

@php
    $sezioneAttiva = 'anagrafiche';

    if (request()->path() == '/') {
        $sezioneAttiva = 'home';
    }

    if (request()->is('preventivi*') || request()->is('righe-preventivo*')) {
        $sezioneAttiva = 'preventivi';
    }

    if (
        request()->is('ordini*') ||
        (isset($ordine))
    ) {
        $sezioneAttiva = 'ordini';
    }

    if (request()->is('interventi*')) {
        $sezioneAttiva = 'assistenza';
    }

    if (request()->is('impostazioni*') || request()->is('fornitori*') || request()->is('prodotti-fornitore*')) {
        $sezioneAttiva = 'impostazioni';
    }
@endphp

{{-- ===================================================== --}}
{{-- SCHEDE SEZIONI --}}
{{-- ===================================================== --}}

<div class="navbar-schede">
    <a href="{{ url('/') }}" class="scheda {{ $sezioneAttiva == 'home' ? 'active' : '' }}">
        Home
    </a>
    <a href="/clienti" class="scheda {{ $sezioneAttiva == 'anagrafiche' ? 'active' : '' }}">
        Anagrafiche
    </a>
    <a href="/preventivi" class="scheda {{ $sezioneAttiva == 'preventivi' ? 'active' : '' }}">
        Preventivi
    </a>
    <a href="/ordini/stato/preparazione_contratto" class="scheda {{ $sezioneAttiva == 'ordini' ? 'active' : '' }}">
        Ordini
    </a>
    <a href="/interventi" class="scheda {{ $sezioneAttiva == 'assistenza' ? 'active' : '' }}">
        Assistenza
    </a>
    <a href="/impostazioni" class="scheda {{ $sezioneAttiva == 'impostazioni' ? 'active' : '' }}" title="Impostazioni">
        ⚙
    </a>
</div>

{{-- ===================================================== --}}
{{-- VOCI SEZIONE HOME --}}
{{-- ===================================================== --}}

@if($sezioneAttiva == 'home')
<div class="navbar-voci">
</div>
@endif

{{-- ===================================================== --}}
{{-- VOCI SEZIONE ANAGRAFICHE --}}
{{-- ===================================================== --}}

@if($sezioneAttiva == 'anagrafiche')
<div class="navbar-voci">
    <a href="/clienti" class="btn {{ request()->is('clienti*') ? 'active' : '' }}">
        Clienti
    </a>
    <a href="/commesse" class="btn {{ request()->is('commesse*') ? 'active' : '' }}">
        Commesse
    </a>
</div>
@endif

{{-- ===================================================== --}}
{{-- VOCI SEZIONE PREVENTIVI --}}
{{-- ===================================================== --}}

@if($sezioneAttiva == 'preventivi')
<div class="navbar-voci">
</div>
@endif

{{-- ===================================================== --}}
{{-- VOCI SEZIONE ORDINI --}}
{{-- ===================================================== --}}

@if($sezioneAttiva == 'ordini')
<div class="navbar-voci">

    <a href="/ordini/stato/preparazione_contratto"
       class="btn {{
            request()->is('ordini/stato/preparazione_contratto') ||
            (isset($ordine) && $ordine->stato == 'preparazione_contratto')
            ? 'active' : ''
       }}">
        Preparazione contratto
        @if(($conteggiOrdini['preparazione_contratto'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['preparazione_contratto'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/stato/in_lavorazione"
       class="btn {{
            request()->is('ordini/stato/in_lavorazione') ||
            (isset($ordine) && $ordine->stato == 'in_lavorazione')
            ? 'active' : ''
       }}">
        In lavorazione
        @if(($conteggiOrdini['in_lavorazione'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['in_lavorazione'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/stato/completo_attesa_merce"
       class="btn {{
            request()->is('ordini/stato/completo_attesa_merce') ||
            (isset($ordine) && $ordine->stato == 'completo_attesa_merce')
            ? 'active' : ''
       }}">
        Attesa merce
        @if(($conteggiOrdini['completo_attesa_merce'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['completo_attesa_merce'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/stato/attesa_saldo_merce"
       class="btn {{
            request()->is('ordini/stato/attesa_saldo_merce') ||
            (isset($ordine) && $ordine->stato == 'attesa_saldo_merce')
            ? 'active' : ''
       }}">
        Saldo a merce pronta
        @if(($conteggiOrdini['attesa_saldo_merce'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['attesa_saldo_merce'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/stato/programmare_posa"
       class="btn {{
            request()->is('ordini/stato/programmare_posa') ||
            (isset($ordine) && $ordine->stato == 'programmare_posa')
            ? 'active' : ''
       }}">
        Programmare posa
        @if(($conteggiOrdini['programmare_posa'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['programmare_posa'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/stato/concluso"
       class="btn {{
            request()->is('ordini/stato/concluso') ||
            (isset($ordine) && $ordine->stato == 'concluso')
            ? 'active' : ''
       }}">
        Posa in corso
        @if(($conteggiOrdini['concluso'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['concluso'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/stato/archiviato"
       class="btn {{
            request()->is('ordini/stato/archiviato') ||
            (isset($ordine) && $ordine->stato == 'archiviato' && !$ordine->archivio_saldo_ricevuto)
            ? 'active' : ''
       }}">
        Conclusi attesa saldo
        @if(($conteggiOrdini['archiviato'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['archiviato'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/archiviati"
       class="btn {{
            request()->is('ordini/archiviati') ||
            (isset($ordine) && $ordine->stato == 'archiviato' && $ordine->archivio_saldo_ricevuto)
            ? 'active' : ''
       }}">
        Archiviati
        @if(($conteggiOrdini['archiviati_enea'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['archiviati_enea'] }}
            </span>
        @endif
    </a>

</div>
@endif

{{-- ===================================================== --}}
{{-- VOCI SEZIONE ASSISTENZA --}}
{{-- ===================================================== --}}

@if($sezioneAttiva == 'assistenza')
<div class="navbar-voci">
    <a href="/interventi" class="btn {{ request()->is('interventi*') ? 'active' : '' }}">
        Interventi
    </a>
</div>
@endif

{{-- ===================================================== --}}
{{-- VOCI SEZIONE IMPOSTAZIONI --}}
{{-- ===================================================== --}}

@if($sezioneAttiva == 'impostazioni')
<div class="navbar-voci">
    <a href="/impostazioni" class="btn {{ request()->is('impostazioni') ? 'active' : '' }}">
        Impostazioni
    </a>
    <a href="/impostazioni/iva" class="btn {{ request()->is('impostazioni/iva*') ? 'active' : '' }}">
        IVA
    </a>
    <a href="/impostazioni/detrazioni" class="btn {{ request()->is('impostazioni/detrazioni*') ? 'active' : '' }}">
        Detrazioni
    </a>
    <a href="/impostazioni/servizi" class="btn {{ request()->is('impostazioni/servizi*') ? 'active' : '' }}">
        Servizi extra
    </a>
    <a href="/impostazioni/tipi-intervento" class="btn {{ request()->is('impostazioni/tipi-intervento*') ? 'active' : '' }}">
        Tipi intervento
    </a>
    <a href="/fornitori" class="btn {{ request()->is('fornitori*') ? 'active' : '' }}">
        Fornitori
    </a>
    <a href="/prodotti-fornitore" class="btn {{ request()->is('prodotti-fornitore*') ? 'active' : '' }}">
        Prodotti fornitore
    </a>
    <a href="/impostazioni/importa-clienti" class="btn {{ request()->is('impostazioni/importa-clienti*') ? 'active' : '' }}">
        Importa clienti Excel
    </a>
    <a href="/impostazioni/backup" class="btn {{ request()->is('impostazioni/backup*') ? 'active' : '' }}">
        Backup
    </a>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('focusin', function (e) {
        let campo = e.target;
        if (campo.matches('input[type="text"], input[type="number"], input[type="email"]')) {
            setTimeout(function () { campo.select(); }, 0);
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.setAttribute('autocomplete', 'off');
        let inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            input.setAttribute('autocomplete', 'off');
            if (input.type === 'text' || input.type === 'email' || input.type === 'tel') {
                input.setAttribute('autocomplete', 'new-password');
            }
        });
    });
});
</script>
{{-- ===================================================== --}}
{{-- CALCOLATRICE RAPIDA --}}
{{-- ===================================================== --}}

<button type="button"
        onclick="apriCalcolatrice()"
        style="
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            border: none;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            z-index: 9998;
        "
        title="Calcolatrice rapida">
    🧮
</button>

<div id="modale_calcolatrice" style="
        display:none;
        position:fixed;
        z-index:9999;
        left:0;
        top:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.45);
    ">

    <div style="
            background:white;
            width:90%;
            max-width:450px;
            margin:80px auto;
            padding:20px;
            border-radius:8px;
        ">

        <h2 style="margin-top:0;">Calcolatrice rapida</h2>

        <div style="display:flex; gap:10px; margin-bottom:20px;">
            <button type="button"
                    id="tab_sconto"
                    onclick="mostraSchedaCalc('sconto')"
                    class="btn btn-azione">
                Trova sconto
            </button>
            <button type="button"
                    id="tab_listino"
                    onclick="mostraSchedaCalc('listino')"
                    class="btn btn-azione">
                Trova listino
            </button>
        </div>

        {{-- SCHEDA 1: TROVA SCONTO APPLICATO --}}
        <div id="scheda_sconto">

            <p>
                Prezzo listino (€)<br>
                <input type="number" id="calc_listino_input" step="0.01">
            </p>

            <p>
                Costo netto (€)<br>
                <input type="number" id="calc_netto_input" step="0.01">
            </p>

            <button type="button" class="btn btn-azione" onclick="calcolaSconto()">
                Calcola
            </button>

            <div id="risultato_sconto" style="
                    margin-top:15px;
                    padding:12px;
                    background:#f9fafb;
                    border:1px solid #ccc;
                    border-radius:6px;
                    display:none;
                ">
            </div>

        </div>

        {{-- SCHEDA 2: TROVA PREZZO LISTINO DI PARTENZA --}}
        <div id="scheda_listino" style="display:none;">

            <p>
                Costo netto desiderato (€)<br>
                <input type="number" id="calc_netto_desiderato" step="0.01">
            </p>

            <p>
                Sconto 1 (%)<br>
                <input type="number" id="calc_sconto1" step="0.01" value="0">
            </p>

            <p>
                Sconto 2 (%) — opzionale<br>
                <input type="number" id="calc_sconto2" step="0.01" value="0">
            </p>

            <p>
                Sconto 3 (%) — opzionale<br>
                <input type="number" id="calc_sconto3" step="0.01" value="0">
            </p>

            <button type="button" class="btn btn-azione" onclick="calcolaListino()">
                Calcola
            </button>

            <div id="risultato_listino" style="
                    margin-top:15px;
                    padding:12px;
                    background:#f9fafb;
                    border:1px solid #ccc;
                    border-radius:6px;
                    display:none;
                ">
            </div>

        </div>

        <div style="margin-top:20px;">
            <button type="button" class="btn btn-azione" onclick="chiudiCalcolatrice()">
                Chiudi
            </button>
        </div>

    </div>

</div>

<script>
function apriCalcolatrice() {
    document.getElementById('modale_calcolatrice').style.display = 'block';
    mostraSchedaCalc('sconto');
}

function chiudiCalcolatrice() {
    document.getElementById('modale_calcolatrice').style.display = 'none';
}

function mostraSchedaCalc(scheda) {
    document.getElementById('scheda_sconto').style.display = scheda === 'sconto' ? 'block' : 'none';
    document.getElementById('scheda_listino').style.display = scheda === 'listino' ? 'block' : 'none';

    document.getElementById('tab_sconto').style.background = scheda === 'sconto' ? '#2563eb' : '#60a5fa';
    document.getElementById('tab_listino').style.background = scheda === 'listino' ? '#2563eb' : '#60a5fa';
}

function calcolaSconto() {
    let listino = parseFloat(document.getElementById('calc_listino_input').value);
    let netto = parseFloat(document.getElementById('calc_netto_input').value);

    let box = document.getElementById('risultato_sconto');

    if (isNaN(listino) || isNaN(netto) || listino <= 0) {
        box.style.display = 'block';
        box.innerHTML = '<strong style="color:red;">Inserisci valori validi.</strong>';
        return;
    }

    let scontoPercentuale = ((listino - netto) / listino) * 100;

    box.style.display = 'block';
    box.innerHTML =
        'Prezzo listino: <strong>' + listino.toFixed(2) + ' €</strong><br>' +
        'Costo netto: <strong>' + netto.toFixed(2) + ' €</strong><br>' +
        'Sconto applicato: <strong>' + scontoPercentuale.toFixed(2) + '%</strong>';
}

function calcolaListino() {
    let netto = parseFloat(document.getElementById('calc_netto_desiderato').value);
    let s1 = parseFloat(document.getElementById('calc_sconto1').value) || 0;
    let s2 = parseFloat(document.getElementById('calc_sconto2').value) || 0;
    let s3 = parseFloat(document.getElementById('calc_sconto3').value) || 0;

    let box = document.getElementById('risultato_listino');

    if (isNaN(netto) || netto <= 0) {
        box.style.display = 'block';
        box.innerHTML = '<strong style="color:red;">Inserisci un costo netto valido.</strong>';
        return;
    }

    let fattoreSconto = (1 - (s1 / 100)) * (1 - (s2 / 100)) * (1 - (s3 / 100));

    if (fattoreSconto <= 0) {
        box.style.display = 'block';
        box.innerHTML = '<strong style="color:red;">Gli sconti inseriti non sono validi (100% o oltre).</strong>';
        return;
    }

    let listino = netto / fattoreSconto;

    let scontiTesto = s1 + '%';
    if (s2 > 0) scontiTesto += ' + ' + s2 + '%';
    if (s3 > 0) scontiTesto += ' + ' + s3 + '%';

    box.style.display = 'block';
    box.innerHTML =
        'Costo netto: <strong>' + netto.toFixed(2) + ' €</strong><br>' +
        'Sconti applicati: <strong>' + scontiTesto + '</strong><br>' +
        'Prezzo listino: <strong>' + listino.toFixed(2) + ' €</strong>';
}

window.addEventListener('click', function(event) {
    let modale = document.getElementById('modale_calcolatrice');
    if (event.target === modale) {
        chiudiCalcolatrice();
    }
});
</script>