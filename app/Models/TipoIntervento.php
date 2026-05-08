<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoIntervento extends Model
{
    protected $table = 'tipi_intervento';

    protected $fillable = [
        'nome',
        'attivo',
        'note',
    ];
}