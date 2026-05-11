@include('partials.menu')

<div class="container">

    <h1>Dettaglio Commessa</h1>

    <div style="margin-bottom:20px;">

        <a href="/commesse" class="btn btn-azione">
            ← Torna alle commesse
        </a>

        <a href="/commesse/{{ $commessa->id }}/edit" class="btn btn-azione">
            Modifica
        </a>

    </div>

    <h2>Cliente e dati base</h2>

    <table class="tabella-dettaglio">

        <tr>
            <th colspan="2">
                {{ $commessa->titolo }}
            </th>
        </tr>

        <tr>
            <td><strong>Cliente</strong></td>
            <td>
                {{ $commessa->cliente?->nome }}
                {{ $commessa->cliente?->cognome }}
            </td>
        </tr>

        <tr>
            <td><strong>Titolo commessa</strong></td>
            <td>{{ $commessa->titolo }}</td>
        </tr>

    </table>

    <h2>Dati intervento</h2>

    <table class="tabella-dettaglio">

        <tr>
            <td><strong>Indirizzo intervento</strong></td>
            <td>{{ $commessa->indirizzo_lavoro }}</td>
        </tr>

        <tr>
            <td><strong>Città intervento</strong></td>
            <td>{{ $commessa->citta_lavoro }}</td>
        </tr>

        <tr>
            <td><strong>Provincia intervento</strong></td>
            <td>{{ $commessa->provincia_lavoro }}</td>
        </tr>

        <tr>
            <td><strong>CAP intervento</strong></td>
            <td>{{ $commessa->cap_lavoro }}</td>
        </tr>

        <tr>
            <td><strong>Piano di posa</strong></td>
            <td>{{ $commessa->piano_posa }}</td>
        </tr>

        <tr>
            <td><strong>Serve autoscala</strong></td>
            <td>{{ $commessa->autoscala ? 'SI' : 'NO' }}</td>
        </tr>

    </table>

    <h2>Tipologia e detrazione</h2>

    <table class="tabella-dettaglio">

        <tr>
            <td><strong>Tipologia abitazione</strong></td>
            <td>{{ $commessa->tipologia_abitazione }}</td>
        </tr>

        <tr>
            <td><strong>Tipo intervento</strong></td>
            <td>{{ $commessa->tipoIntervento?->nome }}</td>
        </tr>

        <tr>
            <td><strong>Tipo detrazione</strong></td>
            <td>{{ $commessa->tipo_detrazione }}</td>
        </tr>

        <tr>
            <td><strong>Percentuale detrazione</strong></td>
            <td>
                @if($commessa->percentuale_detrazione !== null)
                    {{ number_format($commessa->percentuale_detrazione, 2, ',', '.') }}%
                @endif
            </td>
        </tr>

        <tr>
            <td><strong>Pratica ENEA</strong></td>
            <td>{{ $commessa->pratica_enea ? 'SI' : 'NO' }}</td>
        </tr>

    </table>

    <h2>Dati catastali</h2>

    <table class="tabella-dettaglio">

        <tr>
            <td><strong>Foglio</strong></td>
            <td>{{ $commessa->foglio_catastale }}</td>
        </tr>

        <tr>
            <td><strong>Mappale</strong></td>
            <td>{{ $commessa->mappale_catastale }}</td>
        </tr>

        <tr>
            <td><strong>Particella</strong></td>
            <td>{{ $commessa->particella_catastale }}</td>
        </tr>

        <tr>
            <td><strong>Sub</strong></td>
            <td>{{ $commessa->sub_catastale }}</td>
        </tr>

        <tr>
            <td><strong>Numero catastale</strong></td>
            <td>{{ $commessa->numero_catastale }}</td>
        </tr>

        <tr>
            <td><strong>Note catastali</strong></td>
            <td>{!! nl2br(e($commessa->dati_catastali)) !!}</td>
        </tr>

    </table>

    <h2>Pratica edilizia</h2>

    <table class="tabella-dettaglio">

        <tr>
            <td><strong>Tipo pratica edilizia</strong></td>
            <td>{{ $commessa->pratica_edilizia_tipo }}</td>
        </tr>

        <tr>
            <td><strong>Numero pratica edilizia</strong></td>
            <td>{{ $commessa->pratica_edilizia_numero }}</td>
        </tr>

        <tr>
            <td><strong>Protocollo pratica edilizia</strong></td>
            <td>{{ $commessa->pratica_edilizia_protocollo }}</td>
        </tr>

    </table>

    <h2>Note</h2>

    <table class="tabella-dettaglio">

        <tr>
            <td><strong>Note</strong></td>
            <td>{!! nl2br(e($commessa->note)) !!}</td>
        </tr>

    </table>

    <h2>Preventivi collegati</h2>

    <table class="tabella-lista">

        <tr>
            <th>Numero</th>
            <th>Descrizione</th>
            <th>Totale</th>
            <th>Azioni</th>
        </tr>

        @forelse($commessa->preventivi as $preventivo)

            <tr>

                <td>{{ $preventivo->numero }}</td>

                <td>{{ $preventivo->descrizione }}</td>

                <td>
                    {{ number_format($preventivo->totale_cliente_finale, 2, ',', '.') }} €
                </td>

                <td class="azioni">

                    <div class="azioni-bottoni">

                        <a href="/preventivi/{{ $preventivo->id }}" class="btn btn-azione">
                            Apri preventivo
                        </a>

                    </div>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="4">
                    Nessun preventivo collegato.
                </td>
            </tr>

        @endforelse

    </table>

</div>