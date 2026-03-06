<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    //
    protected $table="gestions";
    protected $fillable = [
        'nombre',];

    public function periodos()// 1 gestion tiene muchos periodos
    {
      return $this->hasMany(Periodo::class);
    }
}
