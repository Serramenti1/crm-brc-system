<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fornitore extends Model
{
    use HasFactory;

    protected $table = 'fornitori';

    protected $fillable = [
        'ragione_sociale',
        'referente',
        'telefono',
        'email',
        'sconto_standard_1',
        'sconto_standard_2',
        'sconto_standard_3',
        'note'
    ];
}