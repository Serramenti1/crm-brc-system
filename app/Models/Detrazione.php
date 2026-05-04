<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detrazione extends Model
{
    protected $table = 'detrazioni';

    protected $fillable = [
        'nome',
        'attiva',
    ];

    public function varianti()
    {
        return $this->hasMany(DetrazioneVariante::class, 'detrazione_id');
    }
}