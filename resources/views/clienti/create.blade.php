@include('partials.menu')

<div class="container">

    <h1>Nuovo Cliente</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $errore)
                    <li>{{ $errore }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/clienti">
        @csrf

        <div class="griglia-form">

            <p>
                <label>Nome:</label><br>
                <input type="text" name="nome" value="{{ old('nome') }}">
            </p>

            <p>
                <label>Cognome:</label><br>
                <input type="text" name="cognome" value="{{ old('cognome') }}">
            </p>

            <p>
                <label>Telefono:</label><br>
                <input type="text" name="telefono" value="{{ old('telefono') }}">
            </p>

            <p>
                <label>Email:</label><br>
                <input type="email" name="email" value="{{ old('email') }}">
            </p>

            <p>
                <label>Codice fiscale:</label><br>
                <input type="text" name="codice_fiscale" value="{{ old('codice_fiscale') }}">
            </p>

            <p>
                <label>Partita IVA:</label><br>
                <input type="text" name="partita_iva" value="{{ old('partita_iva') }}">
            </p>

            <p class="campo-doppio">
                <label>Indirizzo:</label><br>
                <input type="text" name="indirizzo" value="{{ old('indirizzo') }}">
            </p>

            <p>
                <label>CAP:</label><br>
                <input type="text" name="cap" value="{{ old('cap') }}">
            </p>

            <p>
                <label>Città:</label><br>
                <input type="text" name="citta" value="{{ old('citta') }}">
            </p>

            <p>
                <label>Provincia:</label><br>
                <input type="text" name="provincia" value="{{ old('provincia') }}">
            </p>

            <p class="campo-triplo">
                <label>Note:</label><br>
                <textarea name="note" rows="4">{{ old('note') }}</textarea>
            </p>

        </div>

        <button type="submit" class="btn btn-azione">
            Salva Cliente
        </button>

        <a href="/clienti" class="btn btn-azione">
            ← Torna ai clienti
        </a>

    </form>

</div>