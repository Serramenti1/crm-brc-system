<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clienti';

    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'telefono',
        'indirizzo',
        'citta',
        'cap',
        'provincia',
        'codice_fiscale',
        'partita_iva'
    ];

    public function commesse()
    {
        return $this->hasMany(Commessa::class);
    }
}