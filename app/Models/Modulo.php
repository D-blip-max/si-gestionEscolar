<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';

    protected $fillable = [
        'idioma_id',
        'nombre',
        'numero_orden',
        'nivel',
        'duracion_meses',
        'prerrequisito_id',
        'es_modulo_graduacion',
    ];

    protected $casts = [
        'numero_orden' => 'integer',
        'duracion_meses' => 'integer',
        'es_modulo_graduacion' => 'boolean',
    ];

    public function idioma()
    {
        return $this->belongsTo(Idioma::class);
    }

    public function prerrequisito()
    {
        return $this->belongsTo(Modulo::class, 'prerrequisito_id');
    }

    public function paralelos()
    {
        return $this->hasMany(Paralelo::class);
    }
}

