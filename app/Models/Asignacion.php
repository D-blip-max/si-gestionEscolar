<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    //

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class);
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function paralelo()
    {
        return $this->belongsTo(Paralelo::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}
