<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commessa extends Model
{
    use HasFactory;

    protected $table = 'commesse';

    protected $fillable = [
        'cliente_id',
        'titolo',
        'indirizzo_lavoro',
        'citta_lavoro',
        'provincia_lavoro',
        'cap_lavoro',
        'tipologia_abitazione',
        'tipo_lavoro',
        'tipo_detrazione',
        'percentuale_detrazione',
        'stato',
        'note'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function preventivi()
    {
        return $this->hasMany(Preventivo::class);
    }
}