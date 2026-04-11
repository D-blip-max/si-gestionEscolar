<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    //
    public function ppff()
    {
        return $this->belongsTo(Ppff::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function matriculaciones()
    {
        return $this->hasMany(Matriculacion::class);
    }


    public function detalleAsistencias()
    {
        return $this->hasMany(DetalleAsistencia::class);
    }

    public function detalleCalificaciones()
    {
        return $this->hasMany(DetalleCalificacion::class);
    }
}
