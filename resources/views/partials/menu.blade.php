<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<!-- BARRA SUPERIORE -->
<div class="topbar">
    CRM BRC SYSTEM
</div>

<!-- MENU -->
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

    <a href="/fornitori" class="btn {{ request()->is('fornitori*') ? 'active' : '' }}">
        Fornitori
    </a>

    <a href="/prodotti-fornitore" class="btn {{ request()->is('prodotti-fornitore*') ? 'active' : '' }}">
        Prodotti Fornitore
    </a>

</div>