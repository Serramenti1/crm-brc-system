<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RigaPreventivoProdotto extends Model
{
    use HasFactory;

    protected $table = 'righe_preventivo_prodotti';

    protected $fillable = [
        'preventivo_id',
        'fornitore_id',
        'descrizione',
        'modalita_calcolo',
        'quantita',
        'prezzo_listino',
        'sconto_fornitore_1',
        'sconto_fornitore_2',
        'sconto_fornitore_3',
        'costo_netto',
        'ricarico_percentuale',
        'prezzo_cliente_unitario',
        'sconto_cliente_percentuale',
        'totale_listino',
        'totale_costo',
        'totale_cliente',
        'ordine_visualizzazione',
        'bene_significativo',
        'note'
    ];

    public function preventivo()
    {
        return $this->belongsTo(Preventivo::class);
    }

    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class);
    }

    public function servizi()
    {
        return $this->hasMany(RigaPreventivoServizio::class, 'riga_prodotto_id');
    }
}