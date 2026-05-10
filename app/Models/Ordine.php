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

        'contratto_firmato',

        'saldo_merce_ricevuto',
        'posa_effettuata',
        'fattura_saldo_posa_fatta',

        'saldo_finale_ricevuto',
        'invio_enea_effettuato',

        'ultimo_avanzamento_tipo',
        'ultimo_avanzamento_riga_id',

        'imponibile_4',
        'imponibile_10',
        'imponibile_22',
        'iva_4',
        'iva_10',
        'iva_22',
        'totale_iva',
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