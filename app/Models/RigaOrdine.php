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
}