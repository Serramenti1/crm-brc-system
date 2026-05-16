<link rel="stylesheet" href="{{ asset('css/style.css') }}?v=999">

<div class="topbar">
    CRM BRC SYSTEM
</div>

<div class="navbar">

    <a href="{{ url('/') }}" class="btn {{ request()->path() == '/' ? 'active' : '' }}">
        Home
    </a>

    <a href="/clienti" class="btn {{ request()->is('clienti*') ? 'active' : '' }}">
        Clienti
    </a>

    <a href="/commesse" class="btn {{ request()->is('commesse*') ? 'active' : '' }}">
        Commesse
    </a>

    <a href="/preventivi" class="btn {{ request()->is('preventivi*') ? 'active' : '' }}">
        Preventivi
    </a>

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
        Conclusi

        @if(($conteggiOrdini['concluso'] ?? 0) > 0)
            <span style="background:red; color:white; padding:2px 6px; border-radius:10px; font-size:12px; margin-left:5px;">
                {{ $conteggiOrdini['concluso'] }}
            </span>
        @endif
    </a>

    <a href="/ordini/stato/archiviato"
   class="btn {{
        request()->is('ordini/stato/archiviato') ||
        (isset($ordine) && $ordine->stato == 'archiviato')
        ? 'active' : ''
   }}">
    Archiviati
</a>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('focusin', function (e) {
        let campo = e.target;

        if (
            campo.matches('input[type="text"], input[type="number"], input[type="email"]')
        ) {
            setTimeout(function () {
                campo.select();
            }, 0);
        }
    });
});
</script>