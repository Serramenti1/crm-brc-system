<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RigaPreventivoServizio extends Model
{
    protected $table = 'riga_preventivo_servizios';

    protected $fillable = [
        'riga_prodotto_id',
        'tipo_servizio',
        'descrizione',
        'costo_brc',
        'ricarico_percentuale',
        'prezzo_cliente',
        'note'
    ];

    // relazione con riga prodotto
    public function rigaProdotto()
    {
        return $this->belongsTo(RigaPreventivoProdotto::class, 'riga_prodotto_id');
    }
}