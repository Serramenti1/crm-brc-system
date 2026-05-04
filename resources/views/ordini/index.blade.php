@include('partials.menu')

@php
    $titoliStato = [
        'in_lavorazione' => 'Ordini in lavorazione',
        'completo_attesa_merce' => 'Ordini in attesa merce',
        'attesa_saldo_merce' => 'Ordini in attesa saldo merce',
        'programmare_posa' => 'Ordini da programmare posa',
        'concluso' => 'Ordini conclusi',
    ];

    $statoCorrente = $stato ?? 'in_lavorazione';
@endphp

<h1>{{ $titoliStato[$statoCorrente] ?? 'Lista Ordini' }}</h1>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<table border="1" cellpadding="5" width="100%">
    <tr>
        <th>Numero</th>
        <th>Cliente</th>
        <th>Preventivo origine</th>
        <th>Costo a noi</th>
        <th>Stato</th>
        <th>Azioni</th>
    </tr>

    @forelse($ordini as $ordine)
        <tr>
            <td>{{ $ordine->numero }}</td>

            <td>
                {{ optional(optional(optional($ordine->commessa)->cliente))->nome }}
                {{ optional(optional(optional($ordine->commessa)->cliente))->cognome }}
            </td>

            <td>
                {{ $ordine->preventivo->numero ?? '' }}
            </td>

            <td>
                {{ number_format($ordine->imponibile, 2, ',', '.') }} €
            </td>

            <td>
                @if($ordine->stato == 'in_lavorazione')
                    In lavorazione
                @elseif($ordine->stato == 'completo_attesa_merce')
                    Completo - attesa merce
                @elseif($ordine->stato == 'attesa_saldo_merce')
                    Attesa saldo merce
                @elseif($ordine->stato == 'programmare_posa')
                    Programmare posa
                @elseif($ordine->stato == 'concluso')
                    Concluso
                @else
                    {{ $ordine->stato }}
                @endif
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
    @empty
        <tr>
            <td colspan="6">Nessun ordine in questa sezione.</td>
        </tr>
    @endforelse
</table>