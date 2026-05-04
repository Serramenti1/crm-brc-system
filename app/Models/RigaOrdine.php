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
        'pdf_path',
    ];

    public function ordine()
    {
        return $this->belongsTo(Ordine::class);
    }

    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class);
    }
}