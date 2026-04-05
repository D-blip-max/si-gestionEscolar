<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    //
    protected $table = "gestions";
    protected $fillable = [
        'nombre',
    ];

    public function periodos() // 1 gestion tiene muchos periodos
    {
        return $this->hasMany(Periodo::class);
    }

    
    public function matriculaciones()
    {
        return $this->hasMany(Matriculacion::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }
}
