@include('partials.menu')

<style>
    .box-selezionato {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        background: #f9fafb;
        margin-top: 5px;
        margin-bottom: 10px;
        max-width: 600px;
    }

    .modale-sfondo {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.45);
    }

    .modale-contenuto {
        background: white;
        width: 85%;
        max-width: 1000px;
        margin: 60px auto;
        padding: 20px;
        border-radius: 8px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .riga-commessa-nascosta {
        display: none;
    }
</style>

<div class="container">

    <h1>Nuovo Preventivo</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $errore)
                    <li>{{ $errore }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/preventivi">
        @csrf

        <p>
            Commessa:<br>

            <input type="hidden"
                   id="commessa_id"
                   name="commessa_id"
                   required>

            <div id="commessa_selezionata" class="box-selezionato">
                Nessuna commessa selezionata
            </div>

            <button type="button"
                    class="btn btn-azione"
                    onclick="apriModaleCommesse()">
                Seleziona commessa
            </button>
        </p>

        <p>
            Descrizione preventivo:<br>
            <input type="text" name="descrizione">
        </p>

        <p>
            Note:<br>
            <textarea name="note"></textarea>
        </p>

        <button type="submit" class="btn btn-azione">
            Crea Preventivo
        </button>

        <a href="/preventivi" class="btn btn-azione">
            Torna alla lista
        </a>

    </form>

</div>

<div id="modale_commesse" class="modale-sfondo">

    <div class="modale-contenuto">

        <h2>Seleziona commessa</h2>

        <input type="text"
               id="ricerca_commessa"
               placeholder="Cerca per cliente, titolo, indirizzo, città..."
               onkeyup="filtraCommesse()">

        <table class="tabella-lista">

            <tr>
                <th>Cliente</th>
                <th>Titolo</th>
                <th>Indirizzo</th>
                <th>Città</th>
                <th>Azioni</th>
            </tr>

            @foreach($commesse as $commessa)

                @php
                    $nomeCliente = $commessa->cliente
                        ? $commessa->cliente->nome . ' ' . $commessa->cliente->cognome
                        : '';

                    $testoRicerca = strtolower(
                        $nomeCliente . ' ' .
                        $commessa->titolo . ' ' .
                        $commessa->indirizzo_lavoro . ' ' .
                        $commessa->citta_lavoro
                    );

                    $testoSelezionato =
                        $nomeCliente .
                        ' - ' .
                        $commessa->titolo .
                        ' - ' .
                        $commessa->indirizzo_lavoro .
                        ' ' .
                        $commessa->citta_lavoro;
                @endphp

                <tr class="riga-commessa"
                    data-ricerca="{{ $testoRicerca }}">

                    <td>{{ $nomeCliente }}</td>
                    <td>{{ $commessa->titolo }}</td>
                    <td>{{ $commessa->indirizzo_lavoro }}</td>
                    <td>{{ $commessa->citta_lavoro }}</td>

                    <td>
                        <button type="button"
                                class="btn btn-azione"
                                onclick="selezionaCommessa(
                                    '{{ $commessa->id }}',
                                    '{{ addslashes($testoSelezionato) }}'
                                )">
                            Seleziona
                        </button>
                    </td>

                </tr>

            @endforeach

        </table>

        <div style="margin-top:20px;">
            <button type="button"
                    class="btn btn-azione"
                    onclick="chiudiModaleCommesse()">
                Chiudi
            </button>
        </div>

    </div>

</div>

<script>
function apriModaleCommesse() {
    document.getElementById('modale_commesse').style.display = 'block';
    document.getElementById('ricerca_commessa').value = '';
    filtraCommesse();
    document.getElementById('ricerca_commessa').focus();
}

function chiudiModaleCommesse() {
    document.getElementById('modale_commesse').style.display = 'none';
}

function selezionaCommessa(id, testo) {
    document.getElementById('commessa_id').value = id;
    document.getElementById('commessa_selezionata').innerHTML = testo;
    chiudiModaleCommesse();
}

function filtraCommesse() {
    let testo = document.getElementById('ricerca_commessa').value.toLowerCase();
    let righe = document.querySelectorAll('.riga-commessa');

    righe.forEach(function (riga) {
        let contenuto = riga.dataset.ricerca;

        if (contenuto.includes(testo)) {
            riga.classList.remove('riga-commessa-nascosta');
        } else {
            riga.classList.add('riga-commessa-nascosta');
        }
    });
}

window.addEventListener('click', function(event) {
    let modale = document.getElementById('modale_commesse');

    if (event.target === modale) {
        chiudiModaleCommesse();
    }
});
</script>