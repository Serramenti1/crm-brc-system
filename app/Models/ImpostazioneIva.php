<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpostazioneIva extends Model
{
    protected $table = 'impostazioni_iva';

    protected $fillable = [
        'nome',
        'aliquota',
        'attiva',
    ];
}