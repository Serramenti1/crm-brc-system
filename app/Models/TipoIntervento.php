<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoIntervento extends Model
{
    protected $table = 'tipi_intervento';

    protected $fillable = [
        'nome',
        'attivo',
        'modalita_iva',
        'impostazione_iva_id',
        'impostazione_iva_secondaria_id',
        'note',
    ];

    public function ivaPrincipale()
    {
        return $this->belongsTo(ImpostazioneIva::class, 'impostazione_iva_id');
    }

    public function ivaSecondaria()
    {
        return $this->belongsTo(ImpostazioneIva::class, 'impostazione_iva_secondaria_id');
    }
}