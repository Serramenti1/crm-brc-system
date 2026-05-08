@include('partials.menu')

@if(session('success'))
    <div style="color:green; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="color:red; margin-bottom:15px;">
        {{ session('error') }}
    </div>
@endif

<div style="margin-bottom:20px;">
    <a href="/ordini/stato/{{ $ordine->stato }}">
        ← Torna alla lista ordini
    </a>
</div>

<h1>Ordine {{ $ordine->numero }}</h1>

<div style="margin-bottom:20px; border:1px solid #ccc; padding:15px;">

    <p>
        <strong>Cliente:</strong>
        {{ $ordine->commessa && $ordine->commessa->cliente
            ? $ordine->commessa->cliente->nome . ' ' . $ordine->commessa->cliente->cognome
            : '' }}
    </p>

    <p>
        <strong>Commessa:</strong>
        {{ $ordine->commessa ? $ordine->commessa->titolo : '' }}
    </p>

    <p>
        <strong>Indirizzo intervento:</strong>
        {{ $ordine->commessa ? $ordine->commessa->indirizzo_lavoro : '' }}
    </p>

    <p>
        <strong>Stato:</strong>

        @if($ordine->stato == 'preparazione_contratto')
            Preparazione contratto
        @elseif($ordine->stato == 'in_lavorazione')
            In lavorazione
        @elseif($ordine->stato == 'completo_attesa_merce')
            Completo - attesa merce
        @elseif($ordine->stato == 'attesa_saldo_merce')
            Attesa saldo merce
        @elseif($ordine->stato == 'programmare_posa')
            Programmare posa
        @elseif($ordine->stato == 'concluso')
            Concluso
        @elseif($ordine->stato == 'archiviato')
            Archiviato
        @endif
    </p>

    @if($ordine->stato != 'preparazione_contratto')
        <form method="POST"
              action="/ordini/{{ $ordine->id }}/stato-precedente"
              style="margin-top:10px;"
              onsubmit="return confermaRitornoStato()">

            @csrf

            <button type="submit">
                ← Torna allo stato precedente
            </button>
        </form>
    @endif

</div>

<h2>Righe ordine</h2>

<table border="1" cellpadding="5" width="100%">

    <tr>
        <th>Prodotto</th>
        <th>Fornitore</th>
        <th>Quantità</th>
        <th>Imponibile</th>
        <th>Stati</th>
        <th>PDF</th>
        <th>Azioni</th>
    </tr>

    @foreach($ordine->righe as $riga)

        <form id="form_riga_{{ $riga->id }}"
              method="POST"
              action="/righe-ordine/{{ $riga->id }}/aggiorna"
              enctype="multipart/form-data"
              onsubmit="return confermaAvanzamentoRiga(this)">
            @csrf
        </form>

        <tr>
            <td>{{ $riga->descrizione }}</td>

            <td>
                {{ $riga->fornitore ? $riga->fornitore->ragione_sociale : '' }}
            </td>

            <td>{{ $riga->quantita }}</td>

            <td>
                {{ number_format($riga->imponibile, 2, ',', '.') }} €
            </td>

            <td>
                @if($ordine->stato == 'preparazione_contratto')
                    In attesa contratto firmato

                @elseif($ordine->stato == 'in_lavorazione')

                    <label>
                        <input type="checkbox"
                               class="chk-inviato"
                               form="form_riga_{{ $riga->id }}"
                               name="inviato"
                               value="1"
                               {{ $riga->inviato ? 'checked' : '' }}>
                        Inviato
                    </label>

                    <br>

                    <label>
                        <input type="checkbox"
                               class="chk-co"
                               form="form_riga_{{ $riga->id }}"
                               name="co_ricevuta"
                               value="1"
                               {{ $riga->co_ricevuta ? 'checked' : '' }}>
                        Conferma ordine ricevuta
                    </label>

                    <br>

                    <label>
                        <input type="checkbox"
                               class="chk-produzione"
                               form="form_riga_{{ $riga->id }}"
                               name="in_produzione"
                               value="1"
                               {{ $riga->in_produzione ? 'checked' : '' }}>
                        In produzione
                    </label>

                @elseif($ordine->stato == 'completo_attesa_merce')

                    <label>
                        <input type="checkbox"
                               class="chk-merce"
                               form="form_riga_{{ $riga->id }}"
                               name="merce_arrivata"
                               value="1"
                               {{ $riga->merce_arrivata ? 'checked' : '' }}>
                        Merce arrivata
                    </label>

                @else
                    -
                @endif
            </td>

            <td>
                @if($riga->pdf_path)
                    <a href="{{ asset('storage/' . $riga->pdf_path) }}" target="_blank">
                        Apri PDF
                    </a>
                    <br>
                @endif

                @if($ordine->stato != 'concluso' && $ordine->stato != 'archiviato')
                    <input type="file"
                           form="form_riga_{{ $riga->id }}"
                           name="pdf"
                           accept="application/pdf">
                @endif
            </td>

            <td>
                @if($ordine->stato == 'in_lavorazione' || $ordine->stato == 'completo_attesa_merce')
                    <button type="submit" form="form_riga_{{ $riga->id }}">
                        Salva
                    </button>
                @else
                    -
                @endif
            </td>
        </tr>

    @endforeach

</table>

@if(
    $ordine->stato == 'preparazione_contratto' ||
    $ordine->stato == 'attesa_saldo_merce' ||
    $ordine->stato == 'programmare_posa' ||
    $ordine->stato == 'concluso'
)

    <br>

    <h2>Stato avanzato ordine</h2>

    <form id="form_stato_avanzato"
          method="POST"
          action="/ordini/{{ $ordine->id }}/aggiorna-stato-avanzato"
          onsubmit="return confermaAvanzamentoAvanzato(this)">

        @csrf

        @if($ordine->stato == 'preparazione_contratto')

            <p>
                <label>
                    <input type="checkbox"
                           id="contratto_firmato"
                           name="contratto_firmato"
                           value="1"
                           {{ $ordine->contratto_firmato ? 'checked' : '' }}>

                    Contratto firmato dal cliente
                </label>
            </p>

        @endif

        @if($ordine->stato == 'attesa_saldo_merce')

            <p>
                <label>
                    <input type="checkbox"
                           id="saldo_merce_ricevuto"
                           name="saldo_merce_ricevuto"
                           value="1"
                           {{ $ordine->saldo_merce_ricevuto ? 'checked' : '' }}>

                    Saldo merce ricevuto
                </label>
            </p>

        @endif

        @if($ordine->stato == 'programmare_posa')

            <p>
                <label>
                    <input type="checkbox"
                           id="posa_effettuata"
                           name="posa_effettuata"
                           value="1"
                           {{ $ordine->posa_effettuata ? 'checked' : '' }}>

                    Posa effettuata
                </label>
            </p>

            <p>
                <label>
                    <input type="checkbox"
                           id="fattura_saldo_posa_fatta"
                           name="fattura_saldo_posa_fatta"
                           value="1"
                           {{ $ordine->fattura_saldo_posa_fatta ? 'checked' : '' }}>

                    Fattura saldo posa fatta
                </label>
            </p>

        @endif

        @if($ordine->stato == 'concluso')

            <p>
                <label>
                    <input type="checkbox"
                           id="saldo_finale_ricevuto"
                           name="saldo_finale_ricevuto"
                           value="1"
                           {{ $ordine->saldo_finale_ricevuto ? 'checked' : '' }}>

                    Saldo finale ricevuto dal cliente
                </label>
            </p>

            @if($ordine->commessa && $ordine->commessa->pratica_enea)
                <p>
                    <label>
                        <input type="checkbox"
                               id="invio_enea_effettuato"
                               name="invio_enea_effettuato"
                               value="1"
                               {{ $ordine->invio_enea_effettuato ? 'checked' : '' }}>

                        Invio ENEA effettuato
                    </label>
                </p>
            @endif

        @endif

        <button type="submit">
            Salva stato avanzato
        </button>

    </form>

@endif

<script>
let statoOrdine = "{{ $ordine->stato }}";

function tutteSpuntate(selector) {
    let elementi = document.querySelectorAll(selector);

    if (elementi.length === 0) {
        return false;
    }

    for (let i = 0; i < elementi.length; i++) {
        if (!elementi[i].checked) {
            return false;
        }
    }

    return true;
}

function confermaAvanzamentoRiga(form) {

    if (statoOrdine === 'in_lavorazione') {
        let tutteInviato = tutteSpuntate('.chk-inviato');
        let tutteCo = tutteSpuntate('.chk-co');
        let tutteProduzione = tutteSpuntate('.chk-produzione');

        if (tutteInviato && tutteCo && tutteProduzione) {
            let conferma = confirm(
                'Tutte le righe risultano complete.\n\nL ordine verrà spostato in: Completo - attesa merce.\n\nConfermi?'
            );

            if (!conferma) {
                form.reset();
                return false;
            }
        }
    }

    if (statoOrdine === 'completo_attesa_merce') {
        let tuttaMerceArrivata = tutteSpuntate('.chk-merce');

        if (tuttaMerceArrivata) {
            let conferma = confirm(
                'Tutta la merce risulta arrivata.\n\nL ordine verrà spostato in: Attesa saldo merce.\n\nConfermi?'
            );

            if (!conferma) {
                form.reset();
                return false;
            }
        }
    }

    return true;
}

function confermaAvanzamentoAvanzato(form) {

    if (statoOrdine === 'preparazione_contratto') {
        let contratto = document.getElementById('contratto_firmato');

        if (contratto && contratto.checked) {
            let conferma = confirm(
                'Contratto firmato.\n\nL ordine verrà spostato in: In lavorazione.\n\nConfermi?'
            );

            if (!conferma) {
                form.reset();
                return false;
            }
        }
    }

    if (statoOrdine === 'attesa_saldo_merce') {
        let saldo = document.getElementById('saldo_merce_ricevuto');

        if (saldo && saldo.checked) {
            let conferma = confirm(
                'Il saldo merce risulta ricevuto.\n\nL ordine verrà spostato in: Programmare posa.\n\nConfermi?'
            );

            if (!conferma) {
                form.reset();
                return false;
            }
        }
    }

    if (statoOrdine === 'programmare_posa') {
        let posa = document.getElementById('posa_effettuata');
        let fattura = document.getElementById('fattura_saldo_posa_fatta');

        if (posa && fattura && posa.checked && fattura.checked) {
            let conferma = confirm(
                'Posa effettuata e fattura saldo posa fatta.\n\nL ordine verrà spostato in: Concluso.\n\nConfermi?'
            );

            if (!conferma) {
                form.reset();
                return false;
            }
        }
    }

    if (statoOrdine === 'concluso') {
        let saldoFinale = document.getElementById('saldo_finale_ricevuto');
        let enea = document.getElementById('invio_enea_effettuato');

        if (saldoFinale && saldoFinale.checked) {
            if (enea) {
                if (enea.checked) {
                    let conferma = confirm(
                        'Saldo finale ricevuto e invio ENEA effettuato.\n\nL ordine verrà spostato in: Archiviato.\n\nConfermi?'
                    );

                    if (!conferma) {
                        form.reset();
                        return false;
                    }
                }
            } else {
                let conferma = confirm(
                    'Saldo finale ricevuto.\n\nL ordine verrà spostato in: Archiviato.\n\nConfermi?'
                );

                if (!conferma) {
                    form.reset();
                    return false;
                }
            }
        }
    }

    return true;
}

function confermaRitornoStato() {
    return confirm(
        'L ordine verrà riportato allo stato precedente e sarà rimossa solo la spunta che aveva causato l avanzamento.\n\nConfermi il ritorno?'
    );
}
</script>