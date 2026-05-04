@include('partials.menu')

<h1>Lista Ordini</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>Numero</th>
        <th>Cliente</th>
        <th>Preventivo origine</th>
        <th>Imponibile scontato</th>
        <th>Stato</th>
        <th>Azioni</th>
    </tr>

    @foreach($ordini as $ordine)
    <tr>@include('partials.menu')

<h1>Ordini in lavorazione</h1>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="5">
    <tr>
        <th>Numero</th>
        <th>Cliente</th>
        <th>Preventivo origine</th>
        <th>Costo a noi</th>
        <th>Stato</th>
        <th>Azioni</th>
    </tr>

    @foreach($ordini as $ordine)
    <tr>
        <td>{{ $ordine->numero }}</td>

        <td>
            {{ optional(optional($ordine->preventivo->commessa)->cliente)->nome }}
            {{ optional(optional($ordine->preventivo->commessa)->cliente)->cognome }}
        </td>

        <td>
            {{ $ordine->preventivo->numero ?? '' }}
        </td>

        <td>
            {{ number_format($ordine->imponibile, 2, ',', '.') }} €
        </td>

        <td>
            In lavorazione
        </td>

        <td>
            <a href="/ordini/{{ $ordine->id }}">Apri</a>

            <form action="/ordini/{{ $ordine->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Eliminare questo ordine?')">
                    Elimina
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
        <td>{{ $ordine->numero }}</td>

        <td>
            {{ optional(optional($ordine->preventivo->commessa)->cliente)->nome }}
            {{ optional(optional($ordine->preventivo->commessa)->cliente)->cognome }}
        </td>

        <td>
            {{ $ordine->preventivo->numero ?? '' }}
        </td>

        <td>
            {{ number_format($ordine->imponibile, 2, ',', '.') }} €
        </td>

        <td>{{ $ordine->stato }}</td>

        <td>
            <a href="/ordini/{{ $ordine->id }}">Apri</a>
        </td>
    </tr>
    @endforeach
</table>