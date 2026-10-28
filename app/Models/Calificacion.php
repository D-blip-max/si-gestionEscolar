<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'inscripcion_id',
        'nota_final',
        'estado',
        'observaciones',
        'updated_by',
        // Campos legacy para compatibilidad
        'asignacion_id',
        'periodo_id',
        'tipo',
        'descripcion',
        'fecha',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    public function evaluador()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relaciones legacy
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id');
    }

    public function detalleCalificaciones()
    {
        return $this->hasMany(DetalleCalificacion::class, 'calificacion_id');
    }
}
