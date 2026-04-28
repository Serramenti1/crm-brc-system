<div class="navbar">

<a href="{{ url('/') }}" class="btn {{ request()->path() == '/' ? 'active' : '' }}">Home</a>

<a href="/clienti" class="btn {{ request()->is('clienti*') ? 'active' : '' }}">Clienti</a>

<a href="/commesse" class="btn {{ request()->is('commesse*') ? 'active' : '' }}">Commesse</a>

<a href="/preventivi" class="btn {{ request()->is('preventivi*') ? 'active' : '' }}">Preventivi</a>

</div>

<style>

.navbar {
    position:fixed;
    top:0;
    left:0;
    width:100%;
    background:#2d3748;
    padding:10px;
    z-index:1000;
}

.btn {
    display:inline-block;
    padding:8px 12px;
    margin-right:5px;
    background:#4a5568;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

.btn:hover {
    background:#2b6cb0;
}

.active {
    background:#1c3d5a;
}

/* Spazio sotto menu */
body {
    margin-top:60px;
}

</style>