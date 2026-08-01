@include('partials.menu')

<div class="container">

    <h1>Nuovo Cliente</h1>

    <p>Seleziona il tipo di cliente da creare:</p>

    <div style="display:flex; gap:20px; margin-top:20px;">

        <a href="{{ route('clienti.createPrivato') }}"
           class="btn btn-azione"
           style="padding:20px 40px; font-size:16px;">
            👤 Privato
        </a>

        <a href="{{ route('clienti.createAzienda') }}"
           class="btn btn-azione"
           style="padding:20px 40px; font-size:16px;">
            🏢 Azienda / Ditta
        </a>

    </div>

    <div style="margin-top:20px;">
        <a href="/clienti" class="btn btn-azione">
            ← Torna ai clienti
        </a>
    </div>

</div>