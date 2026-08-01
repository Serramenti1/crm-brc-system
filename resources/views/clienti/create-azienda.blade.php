@include('partials.menu')

<div class="container">

    <h1>Nuova Azienda / Ditta</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $errore)
                    <li>{{ $errore }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/clienti" onsubmit="return bloccaDoppioInvio(this)">
        @csrf

        <input type="hidden" name="tipo_cliente" value="azienda">

        <div class="griglia-form">

            <p class="campo-doppio">
                <label>Ragione sociale:</label><br>
                <input type="text" name="ragione_sociale" value="{{ old('ragione_sociale') }}" required>
            </p>

            <p>
                <label>Partita IVA:</label><br>
                <input type="text" name="partita_iva" value="{{ old('partita_iva') }}">
            </p>

            <p>
                <label>Codice SDI:</label><br>
                <input type="text" name="codice_sdi" value="{{ old('codice_sdi') }}">
            </p>

            <p>
                <label>PEC:</label><br>
                <input type="email" name="pec" value="{{ old('pec') }}">
            </p>

            <p>
                <label>Nome referente:</label><br>
                <input type="text" name="nome_referente" value="{{ old('nome_referente') }}">
            </p>

            <p>
                <label>Cognome referente:</label><br>
                <input type="text" name="cognome_referente" value="{{ old('cognome_referente') }}">
            </p>

            <p>
                <label>Telefono:</label><br>
                <input type="text" name="telefono" value="{{ old('telefono') }}">
            </p>

            <p>
                <label>Email:</label><br>
                <input type="email" name="email" value="{{ old('email') }}">
            </p>

            <p>
                <label>Codice fiscale:</label><br>
                <input type="text" name="codice_fiscale" value="{{ old('codice_fiscale') }}">
            </p>

            <p class="campo-doppio">
                <label>Indirizzo:</label><br>
                <input type="text" name="indirizzo" value="{{ old('indirizzo') }}">
            </p>

            <p>
                <label>CAP:</label><br>
                <input type="text" name="cap" value="{{ old('cap') }}">
            </p>

            <p>
                <label>Città:</label><br>
                <input type="text" name="citta" value="{{ old('citta') }}">
            </p>

            <p>
                <label>Provincia:</label><br>
                <input type="text" name="provincia" value="{{ old('provincia') }}">
            </p>

            <p class="campo-triplo">
                <label>Note:</label><br>
                <textarea name="note" rows="4">{{ old('note') }}</textarea>
            </p>

        </div>

        <button type="submit" class="btn btn-azione">
            Salva Azienda
        </button>

        <a href="/clienti/scegli-tipo" class="btn btn-azione">
            ← Torna alla scelta
        </a>

    </form>

</div>
<script>
function bloccaDoppioInvio(form) {
    let bottone = form.querySelector('button[type="submit"]');

    if (form.dataset.inviato === '1') {
        return false;
    }

    form.dataset.inviato = '1';

    if (bottone) {
        bottone.disabled = true;
        bottone.innerText = 'Salvataggio...';
    }

    return true;
}
</script>