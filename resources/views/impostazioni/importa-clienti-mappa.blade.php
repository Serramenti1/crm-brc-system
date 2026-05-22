@include('partials.menu')

<div class="container">

    <h1>Mappa colonne Excel</h1>

    @if ($errors->any())

    <div style="background:#fee2e2; color:#991b1b; padding:15px; margin-bottom:20px; border-radius:8px;">

        <strong>Errori:</strong>

        <ul style="margin-top:10px;">

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

@endif

    <form method="POST" action="/impostazioni/importa-clienti/importa">

        @csrf

        <input type="hidden" name="path" value="{{ $path }}">

        @php
            $campiCliente = [
                'nome' => 'Nome',
                'cognome' => 'Cognome',
                'telefono' => 'Telefono',
                'email' => 'Email',
                'indirizzo' => 'Indirizzo',
                'citta' => 'Città',
                'cap' => 'CAP',
                'provincia' => 'Provincia',
                'codice_fiscale' => 'Codice fiscale',
                'partita_iva' => 'Partita IVA',
            ];
        @endphp

        <table class="tabella-dettaglio">

            <tr>
                <th>Campo CRM</th>
                <th>Colonna Excel</th>
            </tr>

            @foreach($campiCliente as $campo => $label)

                <tr>
                    <td>{{ $label }}</td>

                    <td>
                        <select name="mappa[{{ $campo }}]">
                            <option value="">-- Non importare --</option>

                            @foreach($intestazioni as $indice => $intestazione)
                                <option value="{{ $indice }}">
                                    {{ $intestazione }}
                                </option>
                            @endforeach

                        </select>
                    </td>
                </tr>

            @endforeach

        </table>

        <h2>Anteprima prime righe</h2>

        <table class="tabella-lista">
            @foreach($anteprima as $riga)
                <tr>
                    @foreach($riga as $cella)
                        <td>{{ $cella }}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>

        <br>

        <button type="submit" class="btn btn-azione">
            Importa clienti
        </button>

    </form>

</div>