@include('partials.menu')

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
        <select name="commessa_id">
            <option value="">-- Seleziona commessa --</option>
            @foreach($commesse as $commessa)
                <option value="{{ $commessa->id }}">
                    {{ $commessa->cliente ? $commessa->cliente->nome . ' ' . $commessa->cliente->cognome : '' }} - {{ $commessa->titolo }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        Descrizione preventivo:<br>
        <input type="text" name="descrizione">
    </p>

    <p>
        Note:<br>
        <textarea name="note"></textarea>
    </p>

    <button type="submit">Crea Preventivo</button>
</form>

<br>

<a href="/preventivi">Torna alla lista</a>