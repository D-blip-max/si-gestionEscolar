<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    //
    
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function detalleAsistencias()
    {
        return $this->hasMany(DetalleAsistencia::class);
    }
}
