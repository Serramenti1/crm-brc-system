@include('partials.menu')

<h1>Dashboard</h1>

<a href="/impostazioni"
   style="display:inline-block; padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:5px;">
    ⚙️ Impostazioni
</a>

<hr>

<h2>Promemoria azioni necessarie</h2>

<div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">

    <a href="/ordini/stato/preparazione_contratto"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:230px; color:black; border-radius:8px;">
        <h3>Preparazione contratto</h3>
        <p>Ordini in attesa contratto firmato</p>
        <strong style="font-size:28px; color:red;">
            {{ $conteggiOrdini['preparazione_contratto'] ?? 0 }}
        </strong>
    </a>

    <a href="/ordini/stato/in_lavorazione"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:230px; color:black; border-radius:8px;">
        <h3>In lavorazione</h3>
        <p>Ordini da inviare / CO / produzione</p>
        <strong style="font-size:28px; color:red;">
            {{ $conteggiOrdini['in_lavorazione'] ?? 0 }}
        </strong>
    </a>

    <a href="/ordini/stato/completo_attesa_merce"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:230px; color:black; border-radius:8px;">
        <h3>Attesa merce</h3>
        <p>Ordini in attesa arrivo merce</p>
        <strong style="font-size:28px; color:red;">
            {{ $conteggiOrdini['completo_attesa_merce'] ?? 0 }}
        </strong>
    </a>

    <a href="/ordini/stato/attesa_saldo_merce"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:230px; color:black; border-radius:8px;">
        <h3>Attesa saldo</h3>
        <p>Ordini in attesa saldo merce</p>
        <strong style="font-size:28px; color:red;">
            {{ $conteggiOrdini['attesa_saldo_merce'] ?? 0 }}
        </strong>
    </a>

    <a href="/ordini/stato/programmare_posa"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:230px; color:black; border-radius:8px;">
        <h3>Programmare posa</h3>
        <p>Ordini pronti da organizzare per la posa</p>
        <strong style="font-size:28px; color:red;">
            {{ $conteggiOrdini['programmare_posa'] ?? 0 }}
        </strong>
    </a>

    <a href="/ordini/stato/concluso"
       style="border:1px solid #ccc; padding:20px; text-decoration:none; width:230px; color:black; border-radius:8px;">
        <h3>Conclusi</h3>
        <p>Ordini da saldare / ENEA / archiviare</p>
        <strong style="font-size:28px; color:red;">
            {{ $conteggiOrdini['concluso'] ?? 0 }}
        </strong>
    </a>

</div>