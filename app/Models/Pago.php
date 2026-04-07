<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    //
    public function matriculacion()
    {
        return $this->belongsTo(Matriculacion::class);
    }
}
