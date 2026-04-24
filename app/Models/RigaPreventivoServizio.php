<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RigaPreventivoServizio extends Model
{
    use HasFactory;

    protected $table = 'righe_preventivo_servizi';

    public function rigaProdotto()
    {
        return $this->belongsTo(RigaPreventivoProdotto::class, 'riga_prodotto_id');
    }
}