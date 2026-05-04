@include('partials.menu')

<h1>Gestione Detrazioni</h1>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if($errors->any())
    <div style="color:red;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<h3>Aggiungi detrazione</h3>

<form method="POST" action="/impostazioni/detrazioni">
    @csrf

    <input type="text" name="nome" placeholder="Esempio: Risparmio energetico" required>

    <label>
        <input type="checkbox" name="attiva" value="1" checked>
        Attiva
    </label>

    <button>Salva</button>
</form>

<hr>

<h3>Detrazioni</h3>

<table border="1" cellpadding="5" width="100%">
    <tr>
        <th>Nome</th>
        <th>Attiva</th>
        <th>Azioni</th>
    </tr>

    @forelse($detrazioni as $d)
        <tr>
            <form method="POST" action="/impostazioni/detrazioni/{{ $d->id }}">
                @csrf
                @method('PUT')

                <td>
                    <input type="text" name="nome" value="{{ $d->nome }}" required>
                </td>

                <td>
                    <label>
                        <input type="checkbox" name="attiva" value="1" {{ $d->attiva ? 'checked' : '' }}>
                        Attiva
                    </label>
                </td>

                <td>
                    <button>Salva modifica</button>
                </td>
            </form>
        </tr>
    @empty
        <tr>
            <td colspan="3">Nessuna detrazione inserita.</td>
        </tr>
    @endforelse
</table>

<br>

<a href="/impostazioni">← Torna alle impostazioni</a>