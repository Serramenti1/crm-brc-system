@include('partials.menu')

<div class="container">

    <h1>Margini</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div style="color:red; margin-bottom:15px;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/impostazioni/margini">

        @csrf

        <table class="tabella-dettaglio">

            <tr>
                <th colspan="2">Ricarico predefinito</th>
            </tr>

            <tr>
                <td><strong>Ricarico predefinito nuovi prodotti (%)</strong></td>
                <td>
                    <input type="number"
                           name="ricarico_prodotti_default"
                           value="{{ old('ricarico_prodotti_default', $impostazioni->ricarico_prodotti_default) }}"
                           step="0.01"
                           min="0"
                           required>
                </td>
            </tr>

            <tr>
                <th colspan="2">Colori indicatore margine</th>
            </tr>

            <tr>
                <td>
                    <span style="display:inline-block; width:14px; height:14px; background:#dc3545; border-radius:3px; margin-right:8px;"></span>
                    <strong>Rosso sotto (%)</strong>
                </td>
                <td>
                    <input type="number"
                           name="margine_soglia_rossa"
                           value="{{ old('margine_soglia_rossa', $impostazioni->margine_soglia_rossa) }}"
                           step="0.01"
                           min="0"
                           max="100"
                           required>
                </td>
            </tr>

            <tr>
                <td>
                    <span style="display:inline-block; width:14px; height:14px; background:#28a745; border-radius:3px; margin-right:8px;"></span>
                    <strong>Verde da (%)</strong>
                </td>
                <td>
                    <input type="number"
                           name="margine_soglia_verde"
                           value="{{ old('margine_soglia_verde', $impostazioni->margine_soglia_verde) }}"
                           step="0.01"
                           min="0"
                           max="100"
                           required>
                </td>
            </tr>

        </table>

        <p style="margin-top:10px; color:#666; font-size:14px;">
            <span style="color:#fd7e14; font-weight:bold;">Arancione</span> viene applicato automaticamente tra le due soglie.
        </p>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-azione">
                Salva margini
            </button>

            <a href="/impostazioni" class="btn btn-azione">
                ← Torna alle impostazioni
            </a>
        </div>

    </form>

</div>