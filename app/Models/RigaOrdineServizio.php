<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RigaOrdineServizio extends Model
{
    protected $table = 'righe_ordine_servizi';

    protected $fillable = [
        'riga_ordine_id',
        'tipo_servizio',
        'descrizione',
        'costo_brc',
        'ricarico_percentuale',
        'prezzo_cliente',
        'note',
    ];

    public function rigaOrdine()
    {
        return $this->belongsTo(RigaOrdine::class, 'riga_ordine_id');
    }
}