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
        <input type="text" name="nome" placeholder="Esempio: Manutenzione" required>
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

    <button type="submit">Salva</button>
</form>

<hr>

<h3>Tipi intervento salvati</h3>

<table border="1" cellpadding="5" width="100%">
    <tr>
        <th>Nome</th>
        <th>Attivo</th>
        <th>Note</th>
        <th>Azioni</th>
    </tr>

    @forelse($tipiIntervento as $tipo)
        <tr>
            <form method="POST" action="/impostazioni/tipi-intervento/{{ $tipo->id }}">
                @csrf
                @method('PUT')

                <td>
                    <input type="text" name="nome" value="{{ $tipo->nome }}" required>
                </td>

                <td>
                    <input type="checkbox" name="attivo" value="1" {{ $tipo->attivo ? 'checked' : '' }}>
                </td>

                <td>
                    <textarea name="note">{{ $tipo->note }}</textarea>
                </td>

                <td>
                    <button type="submit">Salva modifica</button>
                </td>
            </form>
        </tr>
    @empty
        <tr>
            <td colspan="4">Nessun tipo intervento inserito.</td>
        </tr>
    @endforelse
</table>

<br>

<a href="/impostazioni">← Torna alle impostazioni</a>