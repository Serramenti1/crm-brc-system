@include('partials.menu')

<div class="container">

    <h1>Modifica Riga Prodotto</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $errore)
                    <li>{{ $errore }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/righe-preventivo-prodotti/{{ $riga->id }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="descrizione" value="{{ $riga->descrizione }}">
        <input type="hidden" name="fornitore_id" value="{{ $riga->fornitore_id }}">

        <table class="tabella-dettaglio">

            <tr>
                <th colspan="2">
                    Dati prodotto
                </th>
            </tr>

            <tr>
                <td><strong>Quantità</strong></td>
                <td>
                    <input type="number"
                           name="quantita"
                           step="0.01"
                           value="{{ $riga->quantita }}">
                </td>
            </tr>

            <tr>
                <td><strong>Tipo prezzo</strong></td>
                <td>

                    <div style="display:flex; gap:25px; align-items:center;">

                        <label style="display:flex; align-items:center; gap:8px; width:auto; font-weight:normal;">

                            <input type="radio"
                                   name="modalita_calcolo"
                                   value="da_listino"
                                   {{ $riga->modalita_calcolo == 'da_listino' ? 'checked' : '' }}
                                   onclick="cambiaPrezzoModifica()">

                            Listino

                        </label>

                        <label style="display:flex; align-items:center; gap:8px; width:auto; font-weight:normal;">

                            <input type="radio"
                                   name="modalita_calcolo"
                                   value="da_costo_netto"
                                   {{ $riga->modalita_calcolo == 'da_costo_netto' ? 'checked' : '' }}
                                   onclick="cambiaPrezzoModifica()">

                            Scontato

                        </label>

                    </div>

                </td>
            </tr>

            <tr>
                <td>
                    <strong id="label_prezzo_modifica">
                        Prezzo
                    </strong>
                </td>
                <td>
                    <input type="number"
                           id="input_prezzo_modifica"
                           step="0.01">
                </td>
            </tr>

            <tr>
                <td><strong>Sconto fornitore 1</strong></td>
                <td>
                    <input type="number"
                           name="sconto_fornitore_1"
                           id="sconto_fornitore_1"
                           step="0.01"
                           min="0"
                           max="100"
                           value="{{ $riga->sconto_fornitore_1 }}">
                </td>
            </tr>

            <tr>
                <td><strong>Sconto fornitore 2</strong></td>
                <td>
                    <input type="number"
                           name="sconto_fornitore_2"
                           id="sconto_fornitore_2"
                           step="0.01"
                           min="0"
                           max="100"
                           value="{{ $riga->sconto_fornitore_2 }}">
                </td>
            </tr>

            <tr>
                <td><strong>Sconto fornitore 3</strong></td>
                <td>
                    <input type="number"
                           name="sconto_fornitore_3"
                           id="sconto_fornitore_3"
                           step="0.01"
                           min="0"
                           max="100"
                           value="{{ $riga->sconto_fornitore_3 }}">
                </td>
            </tr>

            <tr>
                <td><strong>Ricarico %</strong></td>
                <td>
                    <input type="number"
                           name="ricarico_percentuale"
                           step="0.01"
                           value="{{ $riga->ricarico_percentuale }}">
                </td>
            </tr>

            <tr>
                <td><strong>Bene significativo</strong></td>
                <td>
                    <label style="display:flex; align-items:center; gap:8px; width:auto; font-weight:normal;">
                        <input type="checkbox"
                               name="bene_significativo"
                               value="1"
                               {{ $riga->bene_significativo ? 'checked' : '' }}>

                        Sì
                    </label>
                </td>
            </tr>

            <tr>
                <td><strong>Ordine visualizzazione</strong></td>
                <td>
                    <input type="number"
                           name="ordine_visualizzazione"
                           value="{{ $riga->ordine_visualizzazione }}">
                </td>
            </tr>

            <tr>
                <td><strong>Note</strong></td>
                <td>
                    <textarea name="note">{{ $riga->note }}</textarea>
                </td>
            </tr>

        </table>

        <div style="margin-top:20px;">

            <button type="submit" class="btn btn-azione">
                Aggiorna Riga Prodotto
            </button>

            <a href="/preventivi/{{ $riga->preventivo_id }}" class="btn btn-azione">
                ← Torna al preventivo
            </a>

        </div>

    </form>

</div>

<script>
function cambiaPrezzoModifica() {

    let tipo = document.querySelector('input[name="modalita_calcolo"]:checked').value;

    let input = document.getElementById('input_prezzo_modifica');
    let label = document.getElementById('label_prezzo_modifica');

    if (tipo === 'da_listino') {

        input.name = 'prezzo_listino';
        input.value = "{{ $riga->prezzo_listino }}";
        label.innerHTML = 'Prezzo listino';

    } else {

        input.name = 'costo_netto';
        input.value = "{{ $riga->costo_netto }}";
        label.innerHTML = 'Prezzo scontato';

    }
}

document.addEventListener('DOMContentLoaded', function () {
    cambiaPrezzoModifica();
});
</script>