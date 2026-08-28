<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impostazione extends Model
{
    protected $table = 'impostazioni';

    protected $fillable = [
    'iva_ordini',
    'ricarico_prodotti_default',
    'margine_soglia_rossa',
    'margine_soglia_verde',
];
}