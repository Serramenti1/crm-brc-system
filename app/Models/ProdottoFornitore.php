<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdottoFornitore extends Model
{
    protected $table = 'prodotti_fornitore';

    protected $fillable = [
        'fornitore_id',
        'descrizione',
        'prezzo_listino',
        'sconto_1',
        'sconto_2',
        'sconto_3'
    ];

    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class);
    }
}