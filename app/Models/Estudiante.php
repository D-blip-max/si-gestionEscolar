<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'usuario_id',
        'codigo_estudiante',
        'nombres',
        'apellidos',
        'ci',
        'fecha_nacimiento',
        'telefono',
        'carrera_facultad',
        'genero',
        'direccion',
        'foto',
        'estado',
        'ppff_id'
    ];

    public function ppff()
    {
        return $this->belongsTo(Ppff::class, 'ppff_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function boletas()
    {
        return $this->hasMany(BoletaPago::class, 'estudiante_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'estudiante_id');
    }

    public function matriculaciones()
    {
        return $this->hasMany(Matriculacion::class, 'estudiante_id');
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->apellidos} {$this->nombres}";
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path($this->foto))) {
            return asset($this->foto);
        }
        return asset('vendor/adminlte/dist/img/avatar.png');
    }
}
