@include('partials.menu')

<div class="container">

    <h1>Modifica Cliente</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $errore)
                    <li>{{ $errore }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/clienti/{{ $cliente->id }}">
        @csrf
        @method('PUT')

        <table class="tabella-dettaglio">

            <tr>
                <th colspan="2">
                    Dati cliente
                </th>
            </tr>

            <tr>
                <td><strong>Nome</strong></td>
                <td>
                    <input type="text" name="nome" value="{{ $cliente->nome }}">
                </td>
            </tr>

            <tr>
                <td><strong>Cognome</strong></td>
                <td>
                    <input type="text" name="cognome" value="{{ $cliente->cognome }}">
                </td>
            </tr>

            <tr>
                <td><strong>Telefono</strong></td>
                <td>
                    <input type="text" name="telefono" value="{{ $cliente->telefono }}">
                </td>
            </tr>

            <tr>
                <td><strong>Email</strong></td>
                <td>
                    <input type="email" name="email" value="{{ $cliente->email }}">
                </td>
            </tr>

            <tr>
                <td><strong>Codice fiscale</strong></td>
                <td>
                    <input type="text" name="codice_fiscale" value="{{ $cliente->codice_fiscale }}">
                </td>
            </tr>

            <tr>
                <td><strong>Partita IVA</strong></td>
                <td>
                    <input type="text" name="partita_iva" value="{{ $cliente->partita_iva }}">
                </td>
            </tr>

            <tr>
                <td><strong>Indirizzo</strong></td>
                <td>
                    <input type="text" name="indirizzo" value="{{ $cliente->indirizzo }}">
                </td>
            </tr>

            <tr>
                <td><strong>CAP</strong></td>
                <td>
                    <input type="text" name="cap" value="{{ $cliente->cap }}">
                </td>
            </tr>

            <tr>
                <td><strong>Città</strong></td>
                <td>
                    <input type="text" name="citta" value="{{ $cliente->citta }}">
                </td>
            </tr>

            <tr>
                <td><strong>Provincia</strong></td>
                <td>
                    <input type="text" name="provincia" value="{{ $cliente->provincia }}">
                </td>
            </tr>

            <tr>
                <td><strong>Note</strong></td>
                <td>
                    <textarea name="note" rows="4">{{ $cliente->note }}</textarea>
                </td>
            </tr>

        </table>

        <div style="margin-top:20px;">

            <button type="submit" class="btn btn-azione">
                Aggiorna Cliente
            </button>

            <a href="/clienti" class="btn btn-azione">
                ← Torna ai clienti
            </a>

            <a href="/clienti/{{ $cliente->id }}" class="btn btn-azione">
                Visualizza cliente
            </a>

        </div>

    </form>

</div>