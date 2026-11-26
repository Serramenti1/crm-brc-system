<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RigaOrdine extends Model
{
    protected $table = 'righe_ordini';

    protected $fillable = [
        'ordine_id',
        'riga_preventivo_prodotto_id',
        'fornitore_id',
        'descrizione',
        'quantita',
        'imponibile',
        'inviato',
        'co_ricevuta',
        'in_produzione',
        'merce_arrivata',
        'merce_parziale',
        'note_merce',
        'pdf_path',
        'modalita_calcolo',
        'prezzo_listino',
        'costo_netto',
        'sconto_fornitore_1',
        'sconto_fornitore_2',
        'sconto_fornitore_3',
        'ricarico_percentuale',
        'bene_significativo',
        'prezzo_cliente_unitario',
        'totale_cliente',
        'totale_costo',
        'note',
    ];

    public function ordine()
    {
        return $this->belongsTo(Ordine::class);
    }

    public function servizi()
{
    return $this->hasMany(RigaOrdineServizio::class, 'riga_ordine_id');
}

    public function fornitore()
{
    return $this->belongsTo(Fornitore::class);
}

public function dettagli()
{
    return $this->hasMany(RigaDettaglioOrdine::class, 'riga_ordine_id')->orderBy('numero_progressivo');
}

// Controllo di riconciliazione tabella dettaglio / riga principale
public function riconciliazioneDettaglio(): array
{
    $sommaProdotto = 0;
    $sommaTrasporto = 0;
    $sommaPosa = 0;

    foreach ($this->dettagli as $d) {
        $pezzi = (float) $d->pezzi;

        $sommaProdotto += $d->nettoClienteProdottoUnitario() * $pezzi;
        $sommaTrasporto += $d->trasportoClienteUnitario() * $pezzi;
        $sommaPosa += (float) $d->posa * $pezzi;
    }

    $servizioTrasporto = 0;
    $servizioPosa = 0;

    foreach ($this->servizi as $s) {
        $importo = (float) $s->prezzo_cliente * (float) $this->quantita;

        if (stripos($s->tipo_servizio, 'trasporto') !== false) {
            $servizioTrasporto += $importo;
        }

        if (stripos($s->tipo_servizio, 'posa') !== false) {
            $servizioPosa += $importo;
        }
    }

    $prodottoOk = abs($sommaProdotto - (float) $this->totale_cliente) < 0.01;
    $trasportoOk = abs($sommaTrasporto - $servizioTrasporto) < 0.01;
    $posaOk = abs($sommaPosa - $servizioPosa) < 0.01;

    return [
        'somma_prodotto' => $sommaProdotto,
        'somma_trasporto' => $sommaTrasporto,
        'somma_posa' => $sommaPosa,
        'servizio_trasporto' => $servizioTrasporto,
        'servizio_posa' => $servizioPosa,
        'prodotto_ok' => $prodottoOk,
        'trasporto_ok' => $trasportoOk,
        'posa_ok' => $posaOk,
        'tutto_ok' => $prodottoOk && $trasportoOk && $posaOk,
    ];
}
}