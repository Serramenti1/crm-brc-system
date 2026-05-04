<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordine extends Model
{
    protected $table = 'ordini';

    protected $fillable = [
        'preventivo_id',
        'commessa_id',
        'numero',
        'imponibile',
        'iva_percentuale',
        'iva_importo',
        'totale_con_iva',
        'stato',
    ];

    public function preventivo()
    {
        return $this->belongsTo(Preventivo::class);
    }

    public function commessa()
    {
        return $this->belongsTo(Commessa::class);
    }

    public function righe()
    {
        return $this->hasMany(RigaOrdine::class);
    }
}