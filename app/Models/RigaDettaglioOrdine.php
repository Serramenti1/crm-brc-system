<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RigaDettaglioOrdine extends Model
{
    protected $table = 'righe_dettaglio_ordine';

    protected $fillable = [
        'riga_ordine_id',
        'numero_progressivo',
        'posizione',
        'descrizione',
        'pezzi',
        'costo_listino',
        'sconto_1',
        'sconto_2',
        'sconto_3',
        'trasporto',
        'posa',
        'ricarico_percentuale',
    ];

    public function rigaOrdine()
    {
        return $this->belongsTo(RigaOrdine::class, 'riga_ordine_id');
    }

    public function nettoBrc()
    {
        $fattoreSconto = (1 - ($this->sconto_1 / 100)) * (1 - ($this->sconto_2 / 100)) * (1 - ($this->sconto_3 / 100));
        return (float) $this->costo_listino * $fattoreSconto;
    }

    public function nettoClienteUnitario()
    {
        return $this->nettoBrc() * (1 + ($this->ricarico_percentuale / 100)) + (float) $this->trasporto + (float) $this->posa;
    }

    public function nettoClienteTotale()
    {
        return $this->nettoClienteUnitario() * (float) $this->pezzi;
    }

    public function scontoApplicato()
    {
        if ((float) $this->costo_listino <= 0) {
            return 0;
        }

        $nettoClienteProdotto = $this->nettoBrc() * (1 + ($this->ricarico_percentuale / 100));

        return (($this->costo_listino - $nettoClienteProdotto) / $this->costo_listino) * 100;
    }
}