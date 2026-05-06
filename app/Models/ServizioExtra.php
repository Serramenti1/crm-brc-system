<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServizioExtra extends Model
{
    protected $table = 'servizi_extra';

    protected $fillable = [
        'nome',
        'costo_brc',
        'ricarico_percentuale',
        'prezzo_cliente',
        'attivo',
        'note',
    ];
}