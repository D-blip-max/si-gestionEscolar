<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaCalificacion extends Model
{
    protected $table = 'auditoria_calificaciones';

    public $timestamps = false;

    protected $fillable = [
        'calificacion_id',
        'inscripcion_id',
        'nota_anterior',
        'nota_nueva',
        'modificado_por',
        'motivo',
        'created_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'modificado_por');
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }
}

