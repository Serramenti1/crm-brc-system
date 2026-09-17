@include('partials.menu')

<h1>Gestione Servizi Extra</h1>

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

<h3>Aggiungi servizio extra</h3>

<form method="POST" action="/impostazioni/servizi">
    @csrf

    <p>
        Nome<br>
        <input type="text" name="nome" placeholder="Esempio: Autoscala" required>
    </p>

    <p>
        Categoria<br>
        <select name="categoria" required>
            <option value="installazione">Installazione</option>
            <option value="servizi" selected>Servizi</option>
        </select>
    </p>

    <p>
        Costo BRC<br>
        <input type="number" name="costo_brc" step="0.01" value="0">
    </p>

    <p>
        Ricarico %<br>
        <input type="number" name="ricarico_percentuale" step="0.01" value="0">
    </p>

    <p>
        Note<br>
        <textarea name="note"></textarea>
    </p>

    <p>
        <label>
            <input type="checkbox" name="attivo" value="1" checked>
            Attivo
        </label>
    </p>

    <button type="submit">Salva nuovo servizio</button>
</form>

<hr>

<h3>Servizi extra salvati</h3>

<table border="1" cellpadding="5" width="100%">
    <tr>
        <th>Nome</th>
        <th>Categoria</th>
        <th>Costo BRC</th>
        <th>Ricarico %</th>
        <th>Prezzo cliente</th>
        <th>Attivo</th>
        <th>Note</th>
        <th>Azioni</th>
    </tr>

    @forelse($servizi as $servizio)
        <tr>

            <td>
                <input type="text" name="nome" value="{{ $servizio->nome }}" form="form-servizio-{{ $servizio->id }}" required>
            </td>

            <td>
                <select name="categoria" form="form-servizio-{{ $servizio->id }}" required>
                    <option value="installazione" {{ $servizio->categoria == 'installazione' ? 'selected' : '' }}>Installazione</option>
                    <option value="servizi" {{ $servizio->categoria == 'servizi' ? 'selected' : '' }}>Servizi</option>
                </select>
            </td>

            <td>
                <input type="number" name="costo_brc" step="0.01" value="{{ $servizio->costo_brc }}" form="form-servizio-{{ $servizio->id }}">
            </td>

            <td>
                <input type="number" name="ricarico_percentuale" step="0.01" value="{{ $servizio->ricarico_percentuale }}" form="form-servizio-{{ $servizio->id }}">
            </td>

            <td>
                {{ number_format($servizio->prezzo_cliente, 2, ',', '.') }} €
            </td>

            <td>
                <label>
                    <input type="checkbox" name="attivo" value="1" form="form-servizio-{{ $servizio->id }}" {{ $servizio->attivo ? 'checked' : '' }}>
                    Attivo
                </label>
            </td>

            <td>
                <textarea name="note" form="form-servizio-{{ $servizio->id }}">{{ $servizio->note }}</textarea>
            </td>

            <td>
                <button type="submit" form="form-servizio-{{ $servizio->id }}">Salva modifica</button>
            </td>

        </tr>
    @empty
        <tr>
            <td colspan="8">Nessun servizio extra inserito.</td>
        </tr>
    @endforelse
</table>

@foreach($servizi as $servizio)
    <form id="form-servizio-{{ $servizio->id }}"
          method="POST"
          action="/impostazioni/servizi/{{ $servizio->id }}">
        @csrf
        @method('PUT')
    </form>
@endforeach

<br>

<div style="margin-top:20px;">

    <a href="/impostazioni" class="btn btn-azione">
        ← Torna alle impostazioni
    </a>

</div>