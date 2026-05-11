@include('partials.menu')

<div class="container">

    <h1>Gestione IVA</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <h3>Aggiungi aliquota IVA</h3>

    <form method="POST" action="/impostazioni/iva">
        @csrf

        <table class="tabella-dettaglio">
            <tr>
                <th colspan="2">Nuova aliquota IVA</th>
            </tr>

            <tr>
                <td><strong>Nome</strong></td>
                <td>
                    <input type="text" name="nome" placeholder="Esempio: IVA standard" required>
                </td>
            </tr>

            <tr>
                <td><strong>Aliquota %</strong></td>
                <td>
                    <input type="number" name="aliquota" step="0.01" placeholder="Esempio: 22" required>
                </td>
            </tr>

            <tr>
                <td><strong>Attiva</strong></td>
                <td>
                    <label>
                        <input type="checkbox" name="attiva" value="1" checked>
                        Attiva
                    </label>
                </td>
            </tr>
        </table>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-azione">
                Salva nuova IVA
            </button>

            <a href="/impostazioni" class="btn btn-azione">
                ← Torna alle impostazioni
            </a>
        </div>
    </form>

    <h3>Aliquote IVA salvate</h3>

    <table class="tabella-lista">
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

                    <td class="azioni">
                        <div class="azioni-bottoni">
                            <button type="submit" class="btn btn-azione">
                                Salva modifica
                            </button>
                        </div>
                    </td>
                </form>
            </tr>
        @empty
            <tr>
                <td colspan="4">Nessuna aliquota IVA inserita.</td>
            </tr>
        @endforelse
    </table>

</div>