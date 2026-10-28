<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoletaPago extends Model
{
    use HasFactory;

    protected $table = 'boletas_pago';

    protected $fillable = [
        'estudiante_id',
        'numero_boleta',
        'monto',
        'fecha_deposito',
        'banco_entidad',
        'concepto',
        'archivo_comprobante',
        'estado',
        'registrado_por'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function usuarioRegistrador()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function inscripcion()
    {
        return $this->hasOne(Inscripcion::class, 'boleta_pago_id');
    }
}

