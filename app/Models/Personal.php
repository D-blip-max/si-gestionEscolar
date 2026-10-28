<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personals';

    protected $fillable = [
        'user_id',
        'tipo',
        'nombres',
        'apellidos',
        'ci',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'profesion',
        'foto',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function formaciones()
    {
        return $this->hasMany(Formacion::class, 'personal_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'personal_id');
    }
}
