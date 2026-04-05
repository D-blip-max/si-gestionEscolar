<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    //
    protected $table = 'turnos';

    protected $fillable = [
        'nombre',
    ];
    
    public function matriculaciones()
    {
        return $this->hasMany(Matriculacion::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class);
    }
}
