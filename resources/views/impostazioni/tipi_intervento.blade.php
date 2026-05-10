@include('partials.menu')

<h1>Tipi intervento</h1>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if($errors->any())
    <div style="color:red;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<h3>Aggiungi tipo intervento</h3>

<form method="POST" action="/impostazioni/tipi-intervento">
    @csrf

    <p>
        Nome<br>
        <input type="text"
               name="nome"
               placeholder="Esempio: Manutenzione"
               required>
    </p>

    <p>
        Modalità IVA<br>

        <select name="modalita_iva" id="modalita_iva_create">
            <option value="iva_unica">
                IVA unica
            </option>

            <option value="beni_significativi">
                IVA mista beni significativi
            </option>
        </select>
    </p>

    <p>
        IVA principale<br>

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
    </p>

    <p>
        IVA secondaria (solo beni significativi)<br>

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
    </p>

    <p>
        Note<br>
        <textarea name="note"></textarea>
    </p>

    <p>
        <label>
            <input type="checkbox"
                   name="attivo"
                   value="1"
                   checked>

            Attivo
        </label>
    </p>

    <button type="submit">
        Salva
    </button>
</form>

<hr>

<h3>Tipi intervento salvati</h3>

<table border="1" cellpadding="5" width="100%">

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
                    <input type="checkbox"
                           name="attivo"
                           value="1"
                           {{ $tipo->attivo ? 'checked' : '' }}>
                </td>

                <td>
                    <textarea name="note">{{ $tipo->note }}</textarea>
                </td>

                <td>
                    <button type="submit">
                        Salva modifica
                    </button>
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

<br>

<a href="/impostazioni">
    ← Torna alle impostazioni
</a>