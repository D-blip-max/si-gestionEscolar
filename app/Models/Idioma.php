<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    protected $table = 'idiomas';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'estado',
    ];

    public function modulos()
    {
        return $this->hasMany(Modulo::class)->orderBy('numero_orden', 'asc');
    }
}
