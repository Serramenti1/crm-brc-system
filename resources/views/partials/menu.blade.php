<link rel="stylesheet" href="{{ asset('css/style.css') }}?v=1002">

<div class="topbar">
    CRM BRC SYSTEM
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
    <a href="{{ url('/') }}" class="btn {{ request()->path() == '/' ? 'active' : '' }}">
        Dashboard
    </a>
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
    <a href="/preventivi" class="btn {{ request()->is('preventivi*') ? 'active' : '' }}">
        Preventivi
    </a>
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
    <a href="/fornitori" class="btn {{ request()->is('fornitori*') ? 'active' : '' }}">
        Fornitori
    </a>
    <a href="/prodotti-fornitore" class="btn {{ request()->is('prodotti-fornitore*') ? 'active' : '' }}">
        Prodotti fornitore
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