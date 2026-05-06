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
        <th>Costo BRC</th>
        <th>Ricarico %</th>
        <th>Prezzo cliente</th>
        <th>Attivo</th>
        <th>Note</th>
        <th>Azioni</th>
    </tr>

    @forelse($servizi as $servizio)
        <tr>
            <form method="POST" action="/impostazioni/servizi/{{ $servizio->id }}">
                @csrf
                @method('PUT')

                <td>
                    <input type="text" name="nome" value="{{ $servizio->nome }}" required>
                </td>

                <td>
                    <input type="number" name="costo_brc" step="0.01" value="{{ $servizio->costo_brc }}">
                </td>

                <td>
                    <input type="number" name="ricarico_percentuale" step="0.01" value="{{ $servizio->ricarico_percentuale }}">
                </td>

                <td>
                    {{ number_format($servizio->prezzo_cliente, 2, ',', '.') }} €
                </td>

                <td>
                    <label>
                        <input type="checkbox" name="attivo" value="1" {{ $servizio->attivo ? 'checked' : '' }}>
                        Attivo
                    </label>
                </td>

                <td>
                    <textarea name="note">{{ $servizio->note }}</textarea>
                </td>

                <td>
                    <button type="submit">Salva modifica</button>
                </td>
            </form>
        </tr>
    @empty
        <tr>
            <td colspan="7">Nessun servizio extra inserito.</td>
        </tr>
    @endforelse
</table>

<br>

<a href="/impostazioni">← Torna alle impostazioni</a>