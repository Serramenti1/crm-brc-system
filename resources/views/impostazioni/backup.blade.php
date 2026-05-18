@include('partials.menu')

<div class="container">

    <h1>Backup / Salvataggi</h1>

    <p>
        Da questa sezione puoi creare un backup completo del gestionale.
    </p>

    <ul>
        <li>Database MySQL</li>
        <li>File caricati in storage/app/public</li>
        <li>File .env</li>
    </ul>

    <form method="POST"
          action="/impostazioni/backup/crea"
          onsubmit="return confirm('Creare un backup completo del CRM?')">

        @csrf

        <button type="submit" class="btn btn-azione">
            Crea backup completo
        </button>

    </form>

</div>