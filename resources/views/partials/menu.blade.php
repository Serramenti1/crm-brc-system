<link rel="stylesheet" href="{{ asset('css/style.css') }}">

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

    <!-- ORDINI NUOVA STRUTTURA -->

    <a href="/ordini/stato/in_lavorazione"
       class="btn {{ request()->is('ordini/stato/in_lavorazione') ? 'active' : '' }}">
        In lavorazione
    </a>

    <a href="/ordini/stato/completo_attesa_merce"
       class="btn {{ request()->is('ordini/stato/completo_attesa_merce') ? 'active' : '' }}">
        Attesa merce
    </a>

    <a href="/ordini/stato/attesa_saldo_merce"
       class="btn {{ request()->is('ordini/stato/attesa_saldo_merce') ? 'active' : '' }}">
        Attesa saldo
    </a>

    <a href="/ordini/stato/programmare_posa"
       class="btn {{ request()->is('ordini/stato/programmare_posa') ? 'active' : '' }}">
        Programmare posa
    </a>

    <a href="/ordini/stato/concluso"
       class="btn {{ request()->is('ordini/stato/concluso') ? 'active' : '' }}">
        Conclusi
    </a>

    <!-- FINE ORDINI -->

    <a href="/fornitori" class="btn {{ request()->is('fornitori*') ? 'active' : '' }}">
        Fornitori
    </a>

    <a href="/prodotti-fornitore" class="btn {{ request()->is('prodotti-fornitore*') ? 'active' : '' }}">
        Prodotti Fornitore
    </a>

</div>