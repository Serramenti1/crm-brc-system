@include('partials.menu')

<h1>Impostazioni</h1>

<p>Da questa sezione puoi gestire le impostazioni generali del gestionale.</p>

<div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">

    <a href="/impostazioni/iva"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:250px; color:black; border-radius:8px;">
        <h3>IVA</h3>
        <p>IVA standard, prima casa, ristrutturazione, manutenzione</p>
    </a>

    <a href="/impostazioni/detrazioni"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:250px; color:black; border-radius:8px;">
        <h3>Detrazioni</h3>
        <p>Tipi detrazione e aliquote detraibilità</p>
    </a>

    <a href="/impostazioni/servizi"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:250px; color:black; border-radius:8px;">
        <h3>Servizi extra</h3>
        <p>Autoscala, trasferta e altri servizi</p>
    </a>

    <a href="/impostazioni/utenti"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:250px; color:black; border-radius:8px;">
        <h3>Utenti</h3>
        <p>Gestione utenti e permessi</p>
    </a>

    <a href="/fornitori"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:250px; color:black; border-radius:8px;">
        <h3>Fornitori</h3>
        <p>Gestione fornitori</p>
    </a>

    <a href="/prodotti-fornitore"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:250px; color:black; border-radius:8px;">
        <h3>Prodotti Fornitore</h3>
        <p>Gestione prodotti e listini fornitori</p>
    </a>

</div>