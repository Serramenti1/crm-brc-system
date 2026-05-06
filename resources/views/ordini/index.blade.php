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
        <th>Cliente</th>
        <th>Commessa</th>
        <th>Costo a noi</th>
        <th>Stato</th>
        <th>Stati righe</th>
        <th>Azioni</th>
    </tr>

    @forelse($ordini as $ordine)
        <tr>
            <td>
                {{ optional(optional($ordine->commessa)->cliente)->nome }}
                {{ optional(optional($ordine->commessa)->cliente)->cognome }}
            </td>

            <td>
                {{ optional($ordine->commessa)->titolo }}
                <br>
                {{ optional($ordine->commessa)->indirizzo_lavoro }}
            </td>

            <td>
                {{ number_format($ordine->imponibile, 2, ',', '.') }} €
            </td>

            <td>
                @if($ordine->stato == 'in_lavorazione')
                    In lavorazione
                @elseif($ordine->stato == 'completo_attesa_merce')
                    Attesa merce
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
                @foreach($ordine->righe as $riga)
                    <div style="margin-bottom:8px; border-bottom:1px solid #ddd; padding-bottom:5px;">
                        <strong>{{ $riga->descrizione }}</strong><br>

                        @if($ordine->stato == 'in_lavorazione')
                            Inviato:
                            {{ $riga->inviato ? 'Sì' : 'No' }}
                            |
                            CO:
                            {{ $riga->co_ricevuta ? 'Sì' : 'No' }}
                            |
                            Produzione:
                            {{ $riga->in_produzione ? 'Sì' : 'No' }}

                        @elseif($ordine->stato == 'completo_attesa_merce')
                            Merce arrivata:
                            {{ $riga->merce_arrivata ? 'Sì' : 'No' }}

                        @else
                            -
                        @endif
                    </div>
                @endforeach
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