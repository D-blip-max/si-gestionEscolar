<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'estudiante_id',
        'modulo_id',
        'paralelo_id',
        'boleta_pago_id',
        'fecha_inscripcion',
        'estado',
        'motivo_anulacion',
        'inscrito_por',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function paralelo()
    {
        return $this->belongsTo(Paralelo::class, 'paralelo_id');
    }

    public function boletaPago()
    {
        return $this->belongsTo(BoletaPago::class, 'boleta_pago_id');
    }

    public function calificacion()
    {
        return $this->hasOne(Calificacion::class, 'inscripcion_id');
    }

    public function usuarioInscriptor()
    {
        return $this->belongsTo(User::class, 'inscrito_por');
    }
}

