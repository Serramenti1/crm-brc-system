@include('partials.menu')

<h1>Gestione IVA</h1>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<h3>Aggiungi aliquota IVA</h3>

<form method="POST" action="/impostazioni/iva">
    @csrf

    <p>
        Nome<br>
        <input type="text" name="nome" placeholder="Esempio: IVA standard" required>
    </p>

    <p>
        Aliquota %<br>
        <input type="number" name="aliquota" step="0.01" placeholder="Esempio: 22" required>
    </p>

    <p>
        <label>
            <input type="checkbox" name="attiva" value="1" checked>
            Attiva
        </label>
    </p>

    <button type="submit">Salva nuova IVA</button>
</form>

<hr>

<h3>Aliquote IVA salvate</h3>

<table border="1" cellpadding="5" width="100%">
    <tr>
        <th>Nome</th>
        <th>Aliquota</th>
        <th>Attiva</th>
        <th>Azioni</th>
    </tr>

    @forelse($iva as $i)
        <tr>
            <form method="POST" action="/impostazioni/iva/{{ $i->id }}">
                @csrf
                @method('PUT')

                <td>
                    <input type="text" name="nome" value="{{ $i->nome }}" required>
                </td>

                <td>
                    <input type="number" name="aliquota" step="0.01" value="{{ $i->aliquota }}" required>
                </td>

                <td>
                    <label>
                        <input type="checkbox" name="attiva" value="1" {{ $i->attiva ? 'checked' : '' }}>
                        Attiva
                    </label>
                </td>

                <td>
                    <button type="submit">Salva modifica</button>
                </td>
            </form>
        </tr>
    @empty
        <tr>
            <td colspan="4">Nessuna aliquota IVA inserita.</td>
        </tr>
    @endforelse
</table>

<br>

<a href="/impostazioni">← Torna alle impostazioni</a>