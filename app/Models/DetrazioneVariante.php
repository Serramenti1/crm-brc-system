<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetrazioneVariante extends Model
{
    protected $table = 'detrazione_varianti';

    protected $fillable = [
        'detrazione_id',
        'tipo_immobile',
        'percentuale',
    ];

    public function detrazione()
    {
        return $this->belongsTo(Detrazione::class, 'detrazione_id');
    }
}