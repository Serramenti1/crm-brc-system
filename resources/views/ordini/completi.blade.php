@include('partials.menu')

<h1>Ordini completi</h1>

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
            Completo
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