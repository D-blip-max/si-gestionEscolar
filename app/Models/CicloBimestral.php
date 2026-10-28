<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicloBimestral extends Model
{
    protected $table = 'ciclos_bimestrales';

    protected $fillable = [
        'nombre',
        'gestion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function paralelos()
    {
        return $this->hasMany(Paralelo::class, 'ciclo_id');
    }
}

