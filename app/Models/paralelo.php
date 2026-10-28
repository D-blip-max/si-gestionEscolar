<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paralelo extends Model
{
    protected $table = 'paralelos';

    protected $fillable = [
        'nombre',
        'ciclo_id',
        'modulo_id',
        'modalidad',
        'aula_o_enlace',
        'turno',
        'hora_inicio',
        'hora_fin',
        'cupo_maximo',
        'cupo_ocupado',
        'grado_id',
    ];

    public function ciclo()
    {
        return $this->belongsTo(CicloBimestral::class, 'ciclo_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function asignacion()
    {
        return $this->hasOne(Asignacion::class, 'paralelo_id');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
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
