<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignacions';

    protected $fillable = [
        'personal_id',
        'paralelo_id',
        'fecha_asignacion',
        'estado',
        'gestion_id',
        'nivel_id',
        'grado_id',
        'materia_id',
        'turno_id',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function docente()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function paralelo()
    {
        return $this->belongsTo(Paralelo::class, 'paralelo_id');
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

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }
}
