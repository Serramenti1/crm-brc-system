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
    'partita_iva',
    'tipo_cliente',
    'ragione_sociale',
    'nome_referente',
    'cognome_referente',
    'codice_sdi',
    'pec',
];
    public function commesse()
    {
        return $this->hasMany(Commessa::class);
    }

    public function nomeVisualizzato()
{
    if ($this->tipo_cliente === 'azienda') {
        return $this->ragione_sociale;
    }

    return trim($this->nome . ' ' . $this->cognome);
}

}