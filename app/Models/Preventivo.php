<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preventivo extends Model
{
    use HasFactory;

    protected $table = 'preventivi';

    protected $fillable = [
        'commessa_id',
        'numero',
        'versione',
        'stato',
        'totale_listino_prodotti',
        'totale_netto_prodotti',
        'totale_servizi_cliente',
        'totale_cliente_finale',
        'sconto_medio_cliente',
        'totale_costo_brc',
        'utile_totale',
        'note'
    ];

    public function commessa()
    {
        return $this->belongsTo(Commessa::class);
    }

    public function righeProdotti()
    {
        return $this->hasMany(RigaPreventivoProdotto::class);
    }
}