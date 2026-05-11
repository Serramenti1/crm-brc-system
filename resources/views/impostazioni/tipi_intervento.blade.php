@include('partials.menu')

<div class="container">

    <h1>Tipi intervento</h1>

    @if(session('success'))
        <p style="color:green;">
            {{ session('success') }}
        </p>
    @endif

    @if($errors->any())

        <div style="color:red; margin-bottom:15px;">

            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach

        </div>

    @endif

    <h3>Aggiungi tipo intervento</h3>

    <form method="POST" action="/impostazioni/tipi-intervento">

        @csrf

        <table class="tabella-dettaglio">

            <tr>
                <th colspan="2">
                    Nuovo tipo intervento
                </th>
            </tr>

            <tr>

                <td>
                    <strong>Nome</strong>
                </td>

                <td>

                    <input type="text"
                           name="nome"
                           placeholder="Esempio: Manutenzione"
                           required>

                </td>

            </tr>

            <tr>

                <td>
                    <strong>Modalità IVA</strong>
                </td>

                <td>

                    <select name="modalita_iva">

                        <option value="iva_unica">
                            IVA unica
                        </option>

                        <option value="beni_significativi">
                            IVA mista beni significativi
                        </option>

                    </select>

                </td>

            </tr>

            <tr>

                <td>
                    <strong>IVA principale</strong>
                </td>

                <td>

                    <select name="impostazione_iva_id">

                        <option value="">
                            -- Seleziona IVA --
                        </option>

                        @foreach($iva as $ivaSingola)

                            <option value="{{ $ivaSingola->id }}">
                                {{ $ivaSingola->nome }} - {{ $ivaSingola->aliquota }}%
                            </option>

                        @endforeach

                    </select>

                </td>

            </tr>

            <tr>

                <td>
                    <strong>IVA secondaria</strong>
                </td>

                <td>

                    <select name="impostazione_iva_secondaria_id">

                        <option value="">
                            -- Nessuna --
                        </option>

                        @foreach($iva as $ivaSingola)

                            <option value="{{ $ivaSingola->id }}">
                                {{ $ivaSingola->nome }} - {{ $ivaSingola->aliquota }}%
                            </option>

                        @endforeach

                    </select>

                </td>

            </tr>

            <tr>

                <td>
                    <strong>Note</strong>
                </td>

                <td>

                    <textarea name="note"></textarea>

                </td>

            </tr>

            <tr>

                <td>
                    <strong>Attivo</strong>
                </td>

                <td>

                    <label style="font-weight:normal;">

                        <input type="checkbox"
                               name="attivo"
                               value="1"
                               checked>

                        Attivo

                    </label>

                </td>

            </tr>

        </table>

        <div style="margin-top:20px;">

            <button type="submit" class="btn btn-azione">
                Salva
            </button>

        </div>

    </form>

    <hr>

    <h3>Tipi intervento salvati</h3>

    <table class="tabella-lista">

        <tr>
            <th>Nome</th>
            <th>Modalità IVA</th>
            <th>IVA principale</th>
            <th>IVA secondaria</th>
            <th>Attivo</th>
            <th>Note</th>
            <th>Azioni</th>
        </tr>

        @forelse($tipiIntervento as $tipo)

            <tr>

                <form method="POST"
                      action="/impostazioni/tipi-intervento/{{ $tipo->id }}">

                    @csrf
                    @method('PUT')

                    <td>

                        <input type="text"
                               name="nome"
                               value="{{ $tipo->nome }}"
                               required>

                    </td>

                    <td>

                        <select name="modalita_iva">

                            <option value="iva_unica"
                                {{ $tipo->modalita_iva == 'iva_unica' ? 'selected' : '' }}>

                                IVA unica

                            </option>

                            <option value="beni_significativi"
                                {{ $tipo->modalita_iva == 'beni_significativi' ? 'selected' : '' }}>

                                IVA mista beni significativi

                            </option>

                        </select>

                    </td>

                    <td>

                        <select name="impostazione_iva_id">

                            <option value="">
                                -- Seleziona IVA --
                            </option>

                            @foreach($iva as $ivaSingola)

                                <option value="{{ $ivaSingola->id }}"
                                    {{ $tipo->impostazione_iva_id == $ivaSingola->id ? 'selected' : '' }}>

                                    {{ $ivaSingola->nome }} - {{ $ivaSingola->aliquota }}%

                                </option>

                            @endforeach

                        </select>

                    </td>

                    <td>

                        <select name="impostazione_iva_secondaria_id">

                            <option value="">
                                -- Nessuna --
                            </option>

                            @foreach($iva as $ivaSingola)

                                <option value="{{ $ivaSingola->id }}"
                                    {{ $tipo->impostazione_iva_secondaria_id == $ivaSingola->id ? 'selected' : '' }}>

                                    {{ $ivaSingola->nome }} - {{ $ivaSingola->aliquota }}%

                                </option>

                            @endforeach

                        </select>

                    </td>

                    <td>

                        <label style="font-weight:normal;">

                            <input type="checkbox"
                                   name="attivo"
                                   value="1"
                                   {{ $tipo->attivo ? 'checked' : '' }}>

                            Attivo

                        </label>

                    </td>

                    <td>

                        <textarea name="note">{{ $tipo->note }}</textarea>

                    </td>

                    <td class="azioni">

                        <div class="azioni-bottoni">

                            <button type="submit"
                                    class="btn btn-azione">

                                Salva modifica

                            </button>

                        </div>

                    </td>

                </form>

            </tr>

        @empty

            <tr>

                <td colspan="7">
                    Nessun tipo intervento inserito.
                </td>

            </tr>

        @endforelse

    </table>

    <div style="margin-top:20px;">

        <a href="/impostazioni" class="btn btn-azione">
            ← Torna alle impostazioni
        </a>

    </div>

</div>